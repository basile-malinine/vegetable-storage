<?php

namespace app\models\Delivery;

use yii\data\ActiveDataProvider;

class DeliverySearch extends Delivery
{
    public function rules()
    {
        return [
            [[
                'id',
                'created_at',
                'supplier_id',
                'own_id',
                'stock_id',
                'manager_id',
                'date_wait',
                'date_close',
                'price',
                'weight',
                'comment'
            ], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = Delivery::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        };

        return $dataProvider;
    }
}
