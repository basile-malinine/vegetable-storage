<?php

namespace app\models\Documents\Order;

use app\models\Documents\Order\OrderItem;
use yii\data\ActiveDataProvider;

class OrderItemSearch extends OrderItem
{
    public function rules(): array
    {
        return [
            [['assortment_name'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = OrderItem::find()
            ->joinWith('order')
            ->joinWith('assortment')
            ->where(['order.id' => $params]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        if (!isset($params['sort'])) {
            $query->orderBy('assortment.name');
        }
        return $dataProvider;
    }
}
