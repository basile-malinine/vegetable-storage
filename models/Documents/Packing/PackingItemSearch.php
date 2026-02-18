<?php

namespace app\models\Documents\Packing;

use yii\data\ActiveDataProvider;

class PackingItemSearch extends PackingItem
{
    public function rules(): array
    {
        return [
            [['assortment_name'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = PackingItem::find()
            ->joinWith('packing')
            ->joinWith('acceptance')
            ->where(['packing.id' => $params]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        if (!isset($params['sort'])) {
            $query->orderBy('acceptance.id');
        }
        return $dataProvider;
    }
}
