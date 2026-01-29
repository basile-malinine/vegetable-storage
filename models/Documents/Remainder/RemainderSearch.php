<?php

namespace app\models\Documents\Remainder;

use yii\data\ActiveDataProvider;

class RemainderSearch extends Remainder
{
    public function search($params)
    {
        $query = Remainder::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }
}