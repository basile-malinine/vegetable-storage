<?php

namespace app\models\Documents\Increase;

use yii\data\ActiveDataProvider;

class IncreaseItemSearch extends IncreaseItem
{
    public function rules(): array
    {
        return [
            [['assortment_name'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = IncreaseItem::find()
            ->joinWith('increase')
            ->joinWith('assortment')
            ->where(['increase.id' => $params]);

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
