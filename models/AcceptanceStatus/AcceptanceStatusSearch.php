<?php

namespace app\models\AcceptanceStatus;

use yii\data\ActiveDataProvider;

class AcceptanceStatusSearch extends AcceptanceStatus
{
    public function rules()
    {
        return [
            [['name'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = AcceptanceStatus::find();
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