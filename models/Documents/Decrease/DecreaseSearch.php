<?php

namespace app\models\Documents\Decrease;

use yii\data\ActiveDataProvider;

class DecreaseSearch extends Decrease
{
    public function rules()
    {
        return [[['id'], 'safe']];
    }

    public function search($params)
    {
        $query = Decrease::find();
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