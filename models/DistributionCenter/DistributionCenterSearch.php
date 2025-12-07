<?php

namespace app\models\DistributionCenter;

use app\models\DistributionCenter\DistributionCenter;
use yii\data\ActiveDataProvider;

class DistributionCenterSearch extends DistributionCenter
{
    public function rules()
    {
        return [
            [['name'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = DistributionCenter::find();
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