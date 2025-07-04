<?php

namespace app\models\Unit;

use yii\data\ActiveDataProvider;

class UnitSearch extends Unit
{
    public function rules()
    {
        return [
            [['name'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = Unit::find();
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