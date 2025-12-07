<?php

namespace app\models\Documents\Delivery;

use app\models\Assortment\Assortment;
use app\models\Base;

/**
 * This is the model class for table "delivery_item".
 *
 * @property int $id
 * @property int|null $delivery_id Доставка
 * @property int $assortment_id Номенклатурная позиция
 * @property int $quantity Количество
 * @property float $price Цена
 * @property float|null $price_total Сумма
 * @property int|null $weight Вес
// * @property int|null $quantity_fact Количество по факту
// * @property float|null $price_total_fact Сумма по факту
// * @property int|null $weight_fact Вес по факту
 *
 * @property Assortment $assortment
 * @property Delivery $delivery
 */
class DeliveryItem extends Base
{
    public mixed $assortment_name;
    public mixed $price_total;
    public mixed $weight;

    public static function tableName()
    {
        return 'delivery_item';
    }

    public function rules()
    {
        return [
            [['delivery_id'], 'default', 'value' => null],
            [['delivery_id', 'assortment_id', 'quantity'], 'integer'],
            [['assortment_id', 'quantity', 'price'], 'required', 'message' => 'Необходимо заполнить'],
            [['price'], 'number', 'numberPattern' => '/^\d+(\.\d{2})?$/'],
            [['assortment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Assortment::class, 'targetAttribute' => ['assortment_id' => 'id']],
            [['delivery_id'], 'exist', 'skipOnError' => true, 'targetClass' => Delivery::class, 'targetAttribute' => ['delivery_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'delivery_id' => 'Доставка',
            'assortment_id' => 'Позиция',
            'quantity' => 'Кол-во',
            'price' => 'Цена',
            'price_total' => 'Сумма',
            'weight' => 'Вес кг',
            'quantity_fact' => 'Кол-во (факт)',
            'price_total_fact' => 'Сумма (факт)',
            'weight_fact' => 'Вес (факт)',
        ];
    }

    public function afterFind()
    {
        parent::afterFind();

        $this->assortment_name = $this->assortment->name;
        $this->price_total = $this->quantity * $this->price;
        $this->weight = $this->quantity * $this->assortment->weight;
    }

    public function getAssortment()
    {
        return $this->hasOne(Assortment::class, ['id' => 'assortment_id']);
    }

    public function getDelivery()
    {
        return $this->hasOne(Delivery::class, ['id' => 'delivery_id']);
    }
}
