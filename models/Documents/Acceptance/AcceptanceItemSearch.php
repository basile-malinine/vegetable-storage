<?php

namespace app\models\Documents\Acceptance;

use yii\data\ActiveDataProvider;

class AcceptanceItemSearch extends AcceptanceItem
{
    public function rules(): array
    {
        return [
            [['assortment_name'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = AcceptanceItem::find()
            ->joinWith('acceptance')
            ->joinWith('assortment')
            ->where(['acceptance.id' => $params]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        if (!isset($params['sort'])) {
            $query->orderBy('assortment.name');
        }
        return $dataProvider;
    }
}