<?php

namespace app\models\Documents\Packing;

use Yii;

use app\models\Documents\Acceptance\Acceptance;
use app\models\Documents\Shipment\Shipment;

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
 * @property Acceptance $acceptance
 * @property Packing $packing
 * @property Shipment $shipment
 */
class PackingItem extends \app\models\Base
{


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
            [['shipment_id', 'quantity_pallet', 'quantity_paks', 'comment'], 'default', 'value' => null],
            [['packing_id', 'acceptance_id', 'quantity'], 'required'],
            [['packing_id', 'acceptance_id', 'shipment_id', 'quantity_pallet', 'quantity_paks'], 'integer'],
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
        ];
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
