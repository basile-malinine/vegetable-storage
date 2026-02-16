<?php

namespace app\models\Documents\Merging;

use app\models\Documents\Shipment\Shipment;
use app\models\Documents\Shipment\ShipmentAcceptance;
use Yii;

use app\models\Base;
use app\models\Documents\Acceptance\Acceptance;
use app\models\Documents\Remainder\Remainder;

/**
 * This is the model class for table "merging_item".
 *
 * @property int $merging_id Объединение
 * @property int $acceptance_id Приёмка
 * @property float $quantity Количество
 * @property int|null $quantity_pallet Количество паллет
 * @property int|null $quantity_paks Количество тары
 * @property string|null $comment Комментарий
 *
 * @property Merging $merging
 * @property Acceptance $acceptance
 */
class MergingItem extends Base
{
    public int|null $pallet_type_id = null;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'merging_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'quantity_pallet',
                'quantity_paks',
                'comment'], 'default', 'value' => null],

            [[
                'merging_id',
                'acceptance_id',
                'quantity'], 'required'],

            [['quantity'], 'number', 'min' => 1],

            [[
                'merging_id',
                'acceptance_id',
                'quantity_pallet',
                'quantity_paks'], 'integer'],

            [['quantity'], 'number'],

            [['comment'], 'string'],

            [['merging_id', 'acceptance_id'], 'unique', 'targetAttribute' => ['merging_id', 'acceptance_id']],
            [['acceptance_id'], 'exist', 'skipOnError' => true, 'targetClass' => Acceptance::class, 'targetAttribute' => ['acceptance_id' => 'id']],
            [['merging_id'], 'exist', 'skipOnError' => true, 'targetClass' => Merging::class, 'targetAttribute' => ['merging_id' => 'id']],

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
                $qntFree = $session->has('merging.free-qnt') ? $session->get('merging.free-qnt')['quantity'] : $qntFree;
                $qnt = $this->quantity;
                break;
            case 'quantity_pallet':
                $qntFree = $session->has('merging.free-qnt') ? $session->get('merging.free-qnt')['quantity_pallet'] : $qntFree;
                $qnt = $this->quantity_pallet;
                break;
            case 'quantity_paks':
                $qntFree = $session->has('merging.free-qnt') ? $session->get('merging.free-qnt')['quantity_paks'] : $qntFree;
                $qnt = $this->quantity_paks;
                break;
        }
        if ($qnt > $qntFree) {
            $this->addError($attribute, 'Максимум ' . $qntFree);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'merging_id' => 'Объединение',
            'acceptance_id' => 'Приёмка',
            'quantity' => 'Количество',
            'quantity_pallet' => 'Количество паллет',
            'quantity_paks' => 'Количество тары',
            'comment' => 'Комментарий',

            'pallet_type_id' => 'Тип паллет',
        ];
    }

    public function afterFind()
    {
        parent::afterFind();

        $item = $this->acceptance->items[0];
        $palletTypeId = $item->pallet_type_id;
    }

    public function beforeSave($insert)
    {
        $session = Yii::$app->session;
        if ($session->has('merging.old_values')) {
            $session->remove('merging.old_values');
        }
        if (!$insert) {
            // Если есть изменения, пишем в сессию старые значения.
            if ($this->oldAttributes['quantity'] != $this->quantity
                || $this->oldAttributes['quantity_pallet'] != $this->quantity_pallet
                || $this->oldAttributes['quantity_paks'] != $this->quantity_paks) {
                $session->set('merging.old_values', [
                    'quantity' => $this->oldAttributes['quantity'],
                    'quantity_pallet' => $this->oldAttributes['quantity_pallet'],
                    'quantity_paks' => $this->oldAttributes['quantity_paks'],
                ]);
            }
        } else {
            $session->set('merging.old_values', [
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
            // Создаём новую отгрузку по Объединению
            $newShipment = new Shipment();
            $newShipment->type_id = Shipment::TYPE_MERGING;
            $newShipment->parent_doc_id = $this->merging_id;
            $newShipment->company_own_id = $this->merging->company_own_id;
            $newShipment->stock_id = $this->merging->stock_id;
            $newShipment->date = $this->merging->date;
            $newShipment->comment = 'Created automatically';
            $newShipment->save();
            // Добавляем позицию для новой Отгрузки
            $shipmentAcceptance = new ShipmentAcceptance();
            $shipmentAcceptance->shipment_id = $newShipment->id;
            $shipmentAcceptance->acceptance_id = $this->acceptance_id;
            $shipmentAcceptance->pallet_type_id = $this->acceptance->items[0]->pallet_type_id;
        } else {
            $acceptanceId = $this->acceptance_id;
            $shipment = null;
            foreach ($this->merging->shipments as $shipment) {
                if ($shipment->shipmentAcceptances[0]->acceptance_id == $acceptanceId) {
                    break;
                }
            }
            $shipmentAcceptance = $shipment->shipmentAcceptances[0];
        }
        // Изменяем кол-во по всем полям
        $shipmentAcceptance->quantity = $this->quantity;
        $shipmentAcceptance->quantity_pallet = $this->quantity_pallet;
        $shipmentAcceptance->quantity_paks = $this->quantity_paks;
        $shipmentAcceptance->save();
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
     * Gets query for [[Merging]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMerging()
    {
        return $this->hasOne(Merging::class, ['id' => 'merging_id']);
    }

    // Возвращает true, если есть изменения.
    public function isChanges(): bool
    {
        return Yii::$app->session->has('merging.old_values');
    }
}
