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
 * @property float|null $shipped Принято РЦ
 * @property float|null $accepted_dist_center Принято РЦ
 * @property string|null $comment Комментарий
 *
 * @property float|null $price_total Сумма
 * @property int|null $weight Вес
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
            [[
                'order_id',
                'shipped',
                'accepted_dist_center',
                'comment'], 'default', 'value' => null
            ],

            [[
                'order_id',
                'assortment_id'], 'integer'
            ],

            [[
                'assortment_id',
                'quantity',
                'price'], 'required', 'message' => 'Необходимо заполнить'
            ],

            [[
                'quantity',
                'accepted_dist_center'], 'number', 'numberPattern' => '/^\d+(\.\d{1})?$/'
            ],

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
            'shipped' => 'Отгружено',
            'accepted_dist_center' => 'Принято РЦ',
            'comment' => 'Комментарий',
            'price_total' => 'Сумма',
            'weight' => 'Вес',
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

        if ($this->shipped) {
            $this->shipped = str_replace(' ', '', $this->shipped);
            $this->shipped = str_replace(',', '.', $this->shipped);
        }
        if ($this->accepted_dist_center) {
            $this->accepted_dist_center = str_replace(' ', '', $this->accepted_dist_center);
            $this->accepted_dist_center = str_replace(',', '.', $this->accepted_dist_center);
        }

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
        $shipped = $this->shipped;
        if (!$this->assortment->unit->is_weight) {
            $shipped = number_format($shipped, 0, '.', '');
        }

        return $this->assortment->name
            . ' ' . $this->quantity
            . ' (' . $this->assortment->unit->name . ')'
            . ', Отгружено: ' . $shipped;
    }
}
