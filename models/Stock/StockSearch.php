<?php

namespace app\models\Stock;

use yii\data\ActiveDataProvider;

class StockSearch extends Stock
{
    public function rules()
    {
        return [
            [['name', 'comment'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = Stock::find();
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