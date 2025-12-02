<?php

namespace app\models\OrderStatus;

use yii\data\ActiveDataProvider;

class OrderStatusSearch extends OrderStatus
{
    public function rules()
    {
        return [
            [['name'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = OrderStatus::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        if (!isset($params['sort'])) {
            $query->orderBy('name');
        }

        return $dataProvider;
    }
}
