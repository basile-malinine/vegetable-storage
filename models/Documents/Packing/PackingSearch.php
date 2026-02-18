<?php

namespace app\models\Documents\Packing;

use yii\data\ActiveDataProvider;

class PackingSearch extends Packing
{
    public function rules()
    {
        return [[['id'], 'safe']];
    }

    public function search($params)
    {
        $query = Packing::find();
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
