<?php

namespace app\models\SystemObject;

use yii\data\ActiveDataProvider;

class SystemObjectSearch extends SystemObject
{
    public function rules()
    {
        return [
            [['name'], 'safe'],
        ];
    }

    public function search($params): ActiveDataProvider
    {
        $query = SystemObject::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        if (!isset($params['sort'])) {
            $query->orderBy('name');
        }

        return $dataProvider;
    }
}
