<?php

namespace app\models\Employee;

use yii\data\ActiveDataProvider;

class EmployeeSearch extends Employee
{
    public function rules()
    {
        return [
            [['surname', 'name', 'last_name'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = Employee::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        if (!isset($params['sort'])) {
            $query->orderBy('surname, name, last_name');
        }

        return $dataProvider;
    }

}