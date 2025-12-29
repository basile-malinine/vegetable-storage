<?php

namespace app\models\Documents\Acceptance;

use app\models\Assortment\Assortment;

/**
 * This is the model class for table "acceptance_item".
 *
 * @property int $acceptance_id Приёмка
 * @property int $assortment_id Номенклатура
 * @property int $pallet_type_id Тип паллета
 * @property float $quantity Количество
 * @property int $quantity_pallet Количество паллет
 * @property int $quantity_paks Количество тары
 * @property string|null $comment Комментарий
 *
 * @property Acceptance $acceptance
 * @property Assortment $assortment
 * @property string $label
 */
class AcceptanceItem extends \app\models\Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'acceptance_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['comment'], 'default', 'value' => null],

            [[
                'acceptance_id',
                'assortment_id'], 'required'
            ],

            [[
                'acceptance_id',
                'assortment_id',
                'pallet_type_id',
                'quantity_pallet',
                'quantity_paks'], 'integer'
            ],


            [['quantity'], 'number', 'numberPattern' => '/^\d+(\.\d{1})?$/'],

            [['comment'], 'string'],

            [[
                'acceptance_id',
                'assortment_id'], 'unique', 'targetAttribute' => ['acceptance_id', 'assortment_id']
            ],

            [['acceptance_id'], 'exist', 'skipOnError' => true, 'targetClass' => Acceptance::class, 'targetAttribute' => ['acceptance_id' => 'id']],
            [['assortment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Assortment::class, 'targetAttribute' => ['assortment_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'acceptance_id' => 'Приёмка',
            'assortment_id' => 'Номенклатура',
            'pallet_type_id' => 'Тип паллет',
            'quantity' => 'Количество',
            'quantity_pallet' => 'Кол-во паллет',
            'quantity_paks' => 'Кол-во тары',
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
     * Gets query for [[Assortment]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAssortment()
    {
        return $this->hasOne(Assortment::class, ['id' => 'assortment_id']);
    }

    public function getLabel()
    {
        $quantity = (bool)$this->assortment->unit->is_weight
            ? number_format($this->quantity, 1, '.', '')
            : number_format($this->quantity, 0, '.', '');

        return $this->assortment->name
            . ' ' . $quantity
            . ' (' . $this->assortment->unit->name . ')';
    }
}
