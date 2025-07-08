<?php

namespace app\models\Unit;

use yii\db\ActiveRecord;


class Unit extends ActiveRecord
{
    public static function tableName()
    {
        return 'unit';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 12],
            [['name'], 'trim'],
            [['name'], 'unique'],
            [['is_weight'], 'integer'],
            [['weight'], 'number', 'numberPattern' => '/^\d+(.\d+)?$/', 'min' => 0.001],
            [['weight'], 'required', 'when' => function ($model) {
                return $model->is_weight;
            }],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'is_weight' => 'Весовая',
            'weight' => 'Вес (кг)',
        ];
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        $this->weight = str_replace(',', '.', $this->weight);

        return true;
    }

    public static function getList()
    {
        return self::find()
            ->select(['name', 'id'])
            ->indexBy('id')
            ->orderBy(['name' => SORT_ASC])
            ->column();
    }
}