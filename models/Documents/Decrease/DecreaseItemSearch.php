<?php

namespace app\models\Documents\Decrease;

use yii\data\ActiveDataProvider;

class DecreaseItemSearch extends DecreaseItem
{
    public function rules(): array
    {
        return [
            [['assortment_name'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = DecreaseItem::find()
            ->joinWith('decrease')
            ->joinWith('assortment')
            ->where(['decrease.id' => $params]);

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
