<?php

namespace app\models;

use yii\db\ActiveRecord;

class Base extends ActiveRecord
{
    // Список Названий
    public static function getList(): array
    {
        return self::find()
            ->select(['name', 'id'])
            ->indexBy('id')
            ->orderBy(['name' => SORT_ASC])
            ->column();
    }
}