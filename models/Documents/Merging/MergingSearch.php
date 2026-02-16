<?php

namespace app\models\Documents\Merging;

use yii\data\ActiveDataProvider;

class MergingSearch extends Merging
{
    public function rules()
    {
        return [[['id'], 'safe']];
    }

    public function search($params)
    {
        $query = Merging::find();
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
