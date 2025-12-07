<?php

namespace app\models;

use yii\db\ActiveRecord;

class Base extends ActiveRecord
{
    // Список Названий
    public static function getList($condition = null): array
    {
        $condition = $condition ?? [];
        return self::find()
            ->select(['name', 'id'])
            ->where($condition)
            ->indexBy('id')
            ->orderBy(['name' => SORT_ASC])
            ->column();
    }
}