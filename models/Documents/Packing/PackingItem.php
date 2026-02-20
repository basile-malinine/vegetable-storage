<?php

namespace app\models\Documents\Packing;

use Yii;

use app\models\Assortment\Assortment;
use app\models\Base;
use app\models\Documents\Acceptance\Acceptance;
use app\models\Documents\Remainder\Remainder;
use app\models\Documents\Shipment\Shipment;
use app\models\Documents\Shipment\ShipmentAcceptance;

/**
 * This is the model class for table "packing_item".
 *
 * @property int $packing_id Фасовка
 * @property int $acceptance_id Приёмка
 * @property int|null $shipment_id Отгрузка
 * @property float $quantity Количество
 * @property int|null $quantity_pallet Количество паллет
 * @property int|null $quantity_paks Количество тары
 * @property string|null $comment Комментарий
 *
 * @property Assortment $assortment
 * @property Acceptance $acceptance
 * @property Packing $packing
 * @property Shipment $shipment
 */
class PackingItem extends Base
{
    public int|null $pallet_type_id = null;
    public float|null $weight = null;
    public Assortment|null $assortment;
    public string $error_qnt = '';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'packing_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'shipment_id',
                'quantity_pallet',
                'quantity_paks', 'comment'], 'default', 'value' => null],

            [[
                'packing_id',
                'acceptance_id',
                'quantity'], 'required'],

            [[
                'packing_id',
                'acceptance_id',
                'shipment_id',
                'quantity_pallet',
                'quantity_paks'], 'integer'],

            [['quantity'], 'number'],

            [['comment', 'error_qnt'], 'string'],

            [['packing_id', 'acceptance_id'], 'unique', 'targetAttribute' => ['packing_id', 'acceptance_id']],

            [['acceptance_id'], 'exist', 'skipOnError' => true, 'targetClass' => Acceptance::class, 'targetAttribute' => ['acceptance_id' => 'id']],
            [['packing_id'], 'exist', 'skipOnError' => true, 'targetClass' => Packing::class, 'targetAttribute' => ['packing_id' => 'id']],
            [['shipment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Shipment::class, 'targetAttribute' => ['shipment_id' => 'id']],

            [[
                'quantity',
                'quantity_pallet',
                'quantity_paks'], 'testQuantity', 'skipOnEmpty' => true],
        ];
    }

    public function testQuantity($attribute, $params)
    {
        $qntFree = Remainder::getFreeByAcceptance($this->acceptance_id, $attribute);
        $qnt = 0;
        $session = Yii::$app->session;
        switch ($attribute) {
            case 'quantity':
                $qntFree = $session->has('packing.free-qnt') ? $session->get('packing.free-qnt')['quantity'] : $qntFree;
                $qnt = $this->quantity;
                break;
            case 'quantity_pallet':
                $qntFree = $session->has('packing.free-qnt') ? $session->get('packing.free-qnt')['quantity_pallet'] : $qntFree;
                $qnt = $this->quantity_pallet;
                break;
            case 'quantity_paks':
                $qntFree = $session->has('packing.free-qnt') ? $session->get('packing.free-qnt')['quantity_paks'] : $qntFree;
                $qnt = $this->quantity_paks;
                break;
        }
        if ($qnt > $qntFree) {
            $this->addError($attribute, 'Максимум ' . $qntFree);
        } elseif ($attribute === 'quantity') {
            $testMultiplicity =
                $this->packing->assortment->getMultiplicityQnt($this->acceptance->items[0]->assortment, $qnt);
            if ($testMultiplicity['min'] != $testMultiplicity['max']) {
                $msgWeightPack = '(' . $this->packing->assortment->weight . ' кг), ближайшее количество: ';
                if ($testMultiplicity['min']) {
                    $msgWeightPack .= $testMultiplicity['min'];
                }
                if ($testMultiplicity['max'] <= $qntFree) {
                    if (!$testMultiplicity['min']) {
                        $msgWeightPack .= $testMultiplicity['max'];
                    } else {
                        $msgWeightPack .= ' или ' . $testMultiplicity['max'];
                    }
                }
                $msgWeightPack .= '.';
                $this->addError($attribute, '');
                $this->addError('error_qnt', 'Количество должно быть кратным фасовке '
                    . $msgWeightPack);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'packing_id' => 'Фасовка',
            'acceptance_id' => 'Приёмка',
            'shipment_id' => 'Отгрузка',
            'quantity' => 'Количество',
            'quantity_pallet' => 'Количество паллет',
            'quantity_paks' => 'Количество тары',
            'comment' => 'Комментарий',

            'pallet_type_id' => 'Тип паллет',
            'weight' => 'Вес',
        ];
    }

    public function afterFind()
    {
        parent::afterFind();

        $item = $this->acceptance->items[0];
        $this->pallet_type_id = $item->pallet_type_id;
        $this->assortment = $this->acceptance->items[0]->assortment;
        $this->weight = $this->quantity * $this->assortment->weight;
    }

    public function beforeSave($insert)
    {
        $session = Yii::$app->session;
        if ($session->has('packing.old_values')) {
            $session->remove('packing.old_values');
        }
        if (!$insert) {
            // При редактировании
            // Если есть изменения, пишем в сессию старые значения.
            if ($this->oldAttributes['quantity'] != $this->quantity
                || $this->oldAttributes['quantity_pallet'] != $this->quantity_pallet
                || $this->oldAttributes['quantity_paks'] != $this->quantity_paks) {
                $session->set('packing.old_values', [
                    'quantity' => $this->oldAttributes['quantity'],
                    'quantity_pallet' => $this->oldAttributes['quantity_pallet'],
                    'quantity_paks' => $this->oldAttributes['quantity_paks'],
                ]);
            }
        } else {
            // При добавлении пишем в сессию новые значения
            $session->set('packing.old_values', [
                'quantity' => $this->quantity,
                'quantity_pallet' => $this->quantity_pallet,
                'quantity_paks' => $this->quantity_paks,
            ]);
        }

        return true;
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            // Создаём новую отгрузку по Фасовке
            $newShipment = new Shipment();
            $newShipment->type_id = Shipment::TYPE_PACKING;
            $newShipment->parent_doc_id = $this->packing_id;
            $newShipment->company_own_id = $this->packing->company_own_id;
            $newShipment->stock_id = $this->packing->stock_id;
            $newShipment->date = $this->packing->date;
            $newShipment->comment = 'Created automatically';
            $newShipment->save();
            $this->updateAttributes(['shipment_id' => $newShipment->id]);
            // Добавляем позицию для новой Отгрузки
            $shipmentAcceptance = new ShipmentAcceptance();
            $shipmentAcceptance->shipment_id = $newShipment->id;
            $shipmentAcceptance->acceptance_id = $this->acceptance_id;
            $shipmentAcceptance->pallet_type_id = $this->acceptance->items[0]->pallet_type_id;
        } else {
            $shipmentAcceptance = $this->shipment->shipmentAcceptances[0];
        }
        // Изменяем кол-во по всем полям
        $shipmentAcceptance->quantity = $this->quantity;
        $shipmentAcceptance->quantity_pallet = $this->quantity_pallet;
        $shipmentAcceptance->quantity_paks = $this->quantity_paks;
        $shipmentAcceptance->save();
    }

    public function afterDelete()
    {
        $this->shipment?->delete();

        return true;
    }

    /**
     * Gets query for [[Acceptance]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcceptance()
    {
        return $this->hasOne(Acceptance::class, ['id' => 'acceptance_id']);
    }

    /**
     * Gets query for [[Assortment]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAssortment()
    {
        return $this->hasOne(Assortment::class, ['id' => $this->acceptance->items[0]->assortment_id]);
    }

    /**
     * Gets query for [[Packing]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPacking()
    {
        return $this->hasOne(Packing::class, ['id' => 'packing_id']);
    }

    /**
     * Gets query for [[Shipment]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShipment()
    {
        return $this->hasOne(Shipment::class, ['id' => 'shipment_id']);
    }

}
