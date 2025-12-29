<?php

namespace app\models\Documents\Shipment;

use yii\data\ActiveDataProvider;

class ShipmentAcceptanceSearch extends ShipmentAcceptance
{
    public function search($params)
    {
        $query = ShipmentAcceptance::find()
            ->joinWith('shipment')
            ->joinWith('acceptance')
            ->where(['shipment.id' => $params]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }
}