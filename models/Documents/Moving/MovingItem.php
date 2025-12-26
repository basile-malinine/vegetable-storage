<?php

namespace app\models\Documents\Moving;

use app\models\Assortment\Assortment;
use app\models\Base;

/**
 * This is the model class for table "moving_item".
 *
 * @property int $moving_id Перемещение
 * @property int $assortment_id Номенклатура
 * @property int $pallet_type_id Тип паллета
 * @property float $quantity Количество
 * @property int $quantity_pallet Количество паллет
 * @property int $quantity_paks Количество тары * @property string|null $comment Комментарий
 *
 * @property Assortment $assortment
 * @property Moving $moving
 */
class MovingItem extends Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'moving_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['comment'], 'default', 'value' => null],
            [[
                'moving_id',
                'assortment_id',
                'quantity'], 'required'
            ],

            [[
                'moving_id',
                'assortment_id',
                'pallet_type_id',
                'quantity_pallet',
                'quantity_paks'], 'integer'
            ],

            [['quantity'], 'number'],

            [['comment'], 'string'],

            [[
                'moving_id',
                'assortment_id'], 'unique', 'targetAttribute' => ['moving_id', 'assortment_id']
            ],

            [['assortment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Assortment::class, 'targetAttribute' => ['assortment_id' => 'id']],
            [['moving_id'], 'exist', 'skipOnError' => true, 'targetClass' => Moving::class, 'targetAttribute' => ['moving_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'moving_id' => 'Перемещение',
            'assortment_id' => 'Номенклатура',
            'pallet_type_id' => 'Тип паллет',
            'quantity' => 'Количество',
            'quantity_pallet' => 'Кол-во паллет',
            'quantity_paks' => 'Кол-во тары',
            'comment' => 'Комментарий',
        ];
    }

    /**
     * Gets query for [[Assortment]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAssortment()
    {
        return $this->hasOne(Assortment::class, ['id' => 'assortment_id']);
    }

    /**
     * Gets query for [[Moving]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMoving()
    {
        return $this->hasOne(Moving::class, ['id' => 'moving_id']);
    }
}
