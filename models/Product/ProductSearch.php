<?php

namespace app\models\Product;

use yii\data\ActiveDataProvider;

class ProductSearch extends Product
{
    public function rules()
    {
        return [
            [['name', 'comment'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = Product::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        if (!isset($parms['sort'])) {
            $query->orderBy('name');
        }

        return $dataProvider;
    }
}