<?php

namespace app\models\Documents\Merging;

use app\models\Documents\Merging\MergingItem;
use yii\data\ActiveDataProvider;

class MergingItemSearch extends MergingItem
{
    public function rules(): array
    {
        return [
            [['assortment_name'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = MergingItem::find()
            ->joinWith('merging')
            ->joinWith('acceptance')
            ->where(['merging.id' => $params]);

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
