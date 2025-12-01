<?php

namespace app\models\PalletType;

use yii\data\ActiveDataProvider;

class PalletTypeSearch extends PalletType
{
    public function rules()
    {
        return [
            [['name'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = PalletType::find();
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