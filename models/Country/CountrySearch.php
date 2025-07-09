<?php

namespace app\models\Country;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class CountrySearch extends Country
{
    public function rules()
    {
        return [
            [['name'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = Country::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        if (!isset($params['sort'])) {
            $query->orderBy(['name' => SORT_ASC]);
        }

        return $dataProvider;
    }
}