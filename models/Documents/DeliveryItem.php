<?php

namespace app\models\Documents;

use app\models\Assortment\Assortment;

/**
 * This is the model class for table "delivery_item".
 *
 * @property int $id
 * @property int $delivery_id Поставка
 * @property int $assortment_id Номенклатура
 * @property float $shipped Отправлено
 * @property float $price Цена
 * @property int $unloading_type_id Тип выгрузки
 * @property int|null $quality_id Качество
 * @property float|null $cost_before_stock Себестоимость до склада
 * @property float|null $price_total Общая стоимость в поставке
 * @property float|null $profit_expected Ожидаемая прибыль
 * @property string|null $work_plan План по работе
 *
 * @property Assortment $assortment
 * @property Delivery $delivery
 */
class DeliveryItem extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'delivery_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['quality_id', 'cost_before_stock', 'price_total', 'profit_expected', 'work_plan'], 'default', 'value' => null],
            [['delivery_id', 'assortment_id', 'shipped', 'price', 'unloading_type_id'], 'required'],
            [['delivery_id', 'assortment_id', 'unloading_type_id', 'quality_id'], 'integer'],
            [['shipped', 'price', 'cost_before_stock', 'price_total', 'profit_expected'], 'number'],
            [['work_plan'], 'string'],
            [['assortment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Assortment::class, 'targetAttribute' => ['assortment_id' => 'id']],
            [['delivery_id'], 'exist', 'skipOnError' => true, 'targetClass' => Delivery::class, 'targetAttribute' => ['delivery_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'delivery_id' => 'Delivery ID',
            'assortment_id' => 'Assortment ID',
            'shipped' => 'Shipped',
            'price' => 'Price',
            'unloading_type_id' => 'Unloading Type ID',
            'quality_id' => 'Quality ID',
            'cost_before_stock' => 'Cost Before Stock',
            'price_total' => 'Price Total',
            'profit_expected' => 'Profit Expected',
            'work_plan' => 'Work Plan',
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
     * Gets query for [[Delivery]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDelivery()
    {
        return $this->hasOne(Delivery::class, ['id' => 'delivery_id']);
    }

}
