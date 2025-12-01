<?php

namespace app\models\TemperatureRegime;

use yii\data\ActiveDataProvider;

class TemperatureRegimeSearch extends TemperatureRegime
{
    public function rules()
    {
        return [
            [['name'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = TemperatureRegime::find();
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