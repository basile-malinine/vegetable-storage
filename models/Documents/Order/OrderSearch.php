<?php

namespace app\models\Documents\Order;

use yii\data\ActiveDataProvider;

class OrderSearch extends Order
{
    public function rules()
    {
        return [
            [['id'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = Order::find();
        if ($params) {
            $query->andWhere([
                'type_id' => $params['type_id'],
                'supplier_id' => $params['supplier_id'],
                'executor_id' => $params['executor_id'],
            ]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        if (!isset($params['sort'])) {
            $query->orderBy('id DESC');
        }

        return $dataProvider;
    }
}
