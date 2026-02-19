<?php

namespace app\models\Documents\Packing;

use app\models\Assortment\Assortment;
use Yii;

use app\models\Documents\Acceptance\Acceptance;
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
class PackingItem extends \app\models\Base
{
    public int|null $pallet_type_id = null;
    public float|null $weight = null;

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

            [['comment'], 'string'],

            [['packing_id', 'acceptance_id'], 'unique', 'targetAttribute' => ['packing_id', 'acceptance_id']],

            [['acceptance_id'], 'exist', 'skipOnError' => true, 'targetClass' => Acceptance::class, 'targetAttribute' => ['acceptance_id' => 'id']],
            [['packing_id'], 'exist', 'skipOnError' => true, 'targetClass' => Packing::class, 'targetAttribute' => ['packing_id' => 'id']],
            [['shipment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Shipment::class, 'targetAttribute' => ['shipment_id' => 'id']],
        ];
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
        $this->weight = $this->quantity * $this->acceptance->items[0]->assortment->weight;
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
