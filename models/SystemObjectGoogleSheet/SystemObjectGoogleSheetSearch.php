<?php

namespace app\models\SystemObjectGoogleSheet;

use yii\data\ActiveDataProvider;

class SystemObjectGoogleSheetSearch extends SystemObjectGoogleSheet
{
    public function rules()
    {
        return [
            [['system_object_id'], 'safe'],
        ];
    }

    public function search($params): ActiveDataProvider
    {
        $query = SystemObjectGoogleSheet::find()
            ->joinWith(['systemObject', 'googleSheet']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        if (!isset($params['sort'])) {
            $query->orderBy('system_object.name');
        }

        return $dataProvider;
    }
}