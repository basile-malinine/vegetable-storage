<?php

namespace app\models\Documents\Order;

use app\models\Assortment\Assortment;
use app\models\Base;

/**
 * This is the model class for table "order_item".
 *
 * @property int $id
 * @property int|null $order_id Доставка
 * @property int $assortment_id Номенклатурная позиция
 * @property float $quantity Количество
 * @property float $price Цена
 * @property float|null $price_total Сумма
 * @property int|null $weight Вес
 * // * @property int|null $quantity_fact Количество по факту
 * // * @property float|null $price_total_fact Сумма по факту
 * // * @property int|null $weight_fact Вес по факту
 *
 * @property Assortment $assortment
 * @property Order $order
 *
 * @property Order $label
 */
class OrderItem extends Base
{
    public mixed $assortment_name;
    public mixed $price_total;
    public mixed $weight;

    public static function tableName()
    {
        return 'order_item';
    }

    public function rules()
    {
        return [
            [['order_id'], 'default', 'value' => null],
            [['order_id', 'assortment_id'], 'integer'],
            [['assortment_id', 'quantity', 'price'], 'required', 'message' => 'Необходимо заполнить'],
            [['quantity'], 'number', 'numberPattern' => '/^\d+(\.\d{1})?$/'],
            [['price'], 'number', 'numberPattern' => '/^\d+(\.\d{2})?$/'],
            [['assortment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Assortment::class, 'targetAttribute' => ['assortment_id' => 'id']],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::class, 'targetAttribute' => ['order_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Доставка',
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

    public function beforeSave($insert)
    {
        $this->quantity = str_replace(' ', '', $this->quantity);
        $this->quantity = str_replace(',', '.', $this->quantity);

        return true;
    }

    public function getAssortment()
    {
        return $this->hasOne(Assortment::class, ['id' => 'assortment_id']);
    }

    public function getOrder()
    {
        return $this->hasOne(Order::class, ['id' => 'order_id']);
    }

    public function getLabel()
    {
        return $this->assortment->name
            . ' ' . $this->quantity
            . ' (' . $this->assortment->unit->name . ')';
    }
}
