<?php

namespace app\models\LegalSubject;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class LegalSubjectSearch extends LegalSubject
{
    public $country;

    public function rules(): array
    {
        return [
            [['name', 'inn', 'country', 'comment'], 'safe'],
        ];
    }

    public function scenarios(): array
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = LegalSubject::find()
        ->joinWith(['country']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        $dataProvider->sort->attributes['name'] = [
            'asc' => ['name' => SORT_ASC],
            'desc' => ['name' => SORT_DESC],
            'default' => SORT_DESC,
        ];
        $dataProvider->sort->attributes['country'] = [
            'asc' => ['country.name' => SORT_ASC, 'name' => SORT_ASC],
            'desc' => ['country.name' => SORT_DESC, 'name' => SORT_ASC],
            'default' => SORT_ASC,
        ];
        $dataProvider->sort->attributes['comment'] = [
            'asc' => ['comment' => SORT_ASC, 'name' => SORT_ASC],
            'desc' => ['comment' => SORT_DESC, 'name' => SORT_ASC],
            'default' => SORT_ASC,
        ];

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere(['like', 'legal_subject.name', $this->name]);
        $query->andFilterWhere(['like', 'inn', $this->inn]);
        $query->andFilterWhere(['like', 'country.name', $this->country]);
        $query->andFilterWhere(['like', 'comment', $this->comment]);

        if (!isset($params['sort'])) {
            $query->orderBy('name');
        }
        return $dataProvider;
    }
}
