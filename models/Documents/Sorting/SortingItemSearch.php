<?php

namespace app\models\Documents\Sorting;

use yii\data\ActiveDataProvider;

class SortingItemSearch extends SortingItem
{
    public function rules(): array
    {
        return [
            [['assortment_name'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = SortingItem::find()
            ->joinWith('sorting')
            ->joinWith('assortment')
            ->where(['sorting.id' => $params]);

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