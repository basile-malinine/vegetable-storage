<?php

namespace app\models\Documents\Shipment;

use app\models\Base;
use app\models\Documents\Acceptance\Acceptance;

/**
 * This is the model class for table "shipment_acceptance".
 *
 * @property int $shipment_id Отгрузка
 * @property int $acceptance_id Приёмка
 * @property int|null $pallet_type_id Тип паллета
 * @property float $quantity Количество
 * @property int|null $quantity_pallet Количество паллет
 * @property int|null $quantity_paks Количество тары
 * @property string|null $comment Комментарий
 *
 * @property Acceptance $acceptance
 * @property Shipment $shipment
 */
class ShipmentAcceptance extends Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shipment_acceptance';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'pallet_type_id',
                'quantity_pallet',
                'quantity_paks',
                'comment'], 'default', 'value' => null
            ],

            [['shipment_id',
                'acceptance_id',
                'quantity'], 'required'
            ],

            [[
                'shipment_id',
                'acceptance_id',
                'pallet_type_id',
                'quantity_pallet',
                'quantity_paks'], 'integer'
            ],

            [['quantity'], 'number'],

            [['comment'], 'string'],

            [['shipment_id', 'acceptance_id'], 'unique', 'targetAttribute' => ['shipment_id', 'acceptance_id']],

            [['acceptance_id'], 'exist', 'skipOnError' => true, 'targetClass' => Acceptance::class, 'targetAttribute' => ['acceptance_id' => 'id']],
            [['shipment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Shipment::class, 'targetAttribute' => ['shipment_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'shipment_id' => 'Отгрузка',
            'acceptance_id' => 'Приёмка',
            'pallet_type_id' => 'Тип паллет',
            'quantity' => 'Кол-во',
            'quantity_pallet' => 'Кол-во паллет',
            'quantity_paks' => 'Кол-во тары',
            'comment' => 'Комментарий',
        ];
    }

    /**
     * ------------------------------------------------------------------------- Приёмка
     * Gets query for [[Acceptance]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAcceptance()
    {
        return $this->hasOne(Acceptance::class, ['id' => 'acceptance_id']);
    }

    /**
     * ------------------------------------------------------------------------- Отгрузка
     * Gets query for [[Shipment]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShipment()
    {
        return $this->hasOne(Shipment::class, ['id' => 'shipment_id']);
    }
}
