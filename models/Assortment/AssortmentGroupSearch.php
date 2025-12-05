<?php

namespace app\models\Assortment;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class AssortmentGroupSearch extends AssortmentGroup
{
    public function rules()
    {
        return [
            [['name'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        if (isset($params['parent_id'])) {
            $query = AssortmentGroup::find()->where('parent_id = ' . $params['parent_id']);
        } else {
            $query = AssortmentGroup::find()->where('parent_id is null');
        }

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