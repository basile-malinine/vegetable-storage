<?php

namespace app\models\Documents\Delivery;

use yii\data\ActiveDataProvider;

class DeliveryItemSearch extends DeliveryItem
{
    public function rules(): array
    {
        return [
            [['assortment_name'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = DeliveryItem::find()
            ->joinWith('delivery')
            ->joinWith('assortment')
            ->where(['delivery.id' => $params]);

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
