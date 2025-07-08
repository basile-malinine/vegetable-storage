<?php

namespace app\models\Assortment;

use yii\data\ActiveDataProvider;

class AssortmentSearch extends Assortment
{
    public function rules()
    {
        return [
            [['name', 'weight'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = Assortment::find();
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