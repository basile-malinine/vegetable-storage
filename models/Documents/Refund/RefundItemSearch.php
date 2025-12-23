<?php

namespace app\models\Documents\Refund;

use yii\data\ActiveDataProvider;

class RefundItemSearch extends RefundItem
{
    public function rules(): array
    {
        return [
            [['assortment_name'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = RefundItem::find()
            ->joinWith('refund')
            ->joinWith('assortment')
            ->where(['refund.id' => $params]);

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
