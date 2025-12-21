<?php

namespace app\models\Documents\Acceptance;

use yii\data\ActiveDataProvider;

class AcceptanceSearch extends Acceptance
{
    public function rules()
    {
        return [[['id'], 'safe']];
    }

    public function search($params)
    {
        $query = Acceptance::find();
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