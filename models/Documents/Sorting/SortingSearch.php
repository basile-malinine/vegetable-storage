<?php

namespace app\models\Documents\Sorting;

use yii\data\ActiveDataProvider;

class SortingSearch extends Sorting
{
    public function rules()
    {
        return [[['id'], 'safe']];
    }

    public function search($params)
    {
        $query = Sorting::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        };

        if (!isset($params['sort'])) {
            $query->orderBy('id DESC');
        }

        return $dataProvider;
    }
}