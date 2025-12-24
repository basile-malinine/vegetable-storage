<?php

namespace app\models\Documents\Refund;

use app\models\Assortment\Assortment;
use app\models\Base;

/**
 * This is the model class for table "refund_item".
 *
 * @property int $refund_id Возврат
 * @property int $assortment_id Номенклатура
 * @property float $quantity Количество
 * @property string|null $comment Комментарий
 *
 * @property Assortment $assortment
 * @property Refund $refund
 * @property string $label
 */
class RefundItem extends Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'refund_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['comment'], 'default', 'value' => null],

            [[
                'refund_id',
                'assortment_id',
                'quantity'], 'required'
            ],

            [[
                'refund_id',
                'assortment_id'], 'integer'
            ],

            [['comment'], 'string'],

            [['quantity'], 'number', 'numberPattern' => '/^\d+(\.\d{1})?$/'],

            [['refund_id', 'assortment_id'], 'unique', 'targetAttribute' => ['refund_id', 'assortment_id']],
            [['assortment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Assortment::class, 'targetAttribute' => ['assortment_id' => 'id']],
            [['refund_id'], 'exist', 'skipOnError' => true, 'targetClass' => Refund::class, 'targetAttribute' => ['refund_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'refund_id' => 'ID',
            'assortment_id' => 'Номенклатура',
            'quantity' => 'Количество',
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
     * Gets query for [[Refund]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRefund()
    {
        return $this->hasOne(Refund::class, ['id' => 'refund_id']);
    }
    public function getLabel()
    {
        return $this->assortment->name
            . ' ' . $this->quantity
            . ' (' . $this->assortment->unit->name . ')';
    }
}
