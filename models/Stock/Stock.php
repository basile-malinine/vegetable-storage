<?php

namespace app\models\Stock;

use app\models\GoogleBase;
use app\models\SystemObject\SystemObject;
use app\models\UpdateGoogle;

/**
 * This is the model class for table "stock".
 *
 * @property int $id ID
 * @property string $name Название
 * @property string|null $address Адрес
 * @property string|null $comment Комментарий
 *
 */
class Stock extends GoogleBase
{
    public static function tableName()
    {
        return 'stock';
    }

    public function rules()
    {
        return [
            [['address', 'comment'], 'default', 'value' => null],
            [['name'], 'required'],
            [['comment'], 'string'],
            [['name'], 'string', 'max' => 30],
            [['address'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'address' => 'Адрес',
            'comment' => 'Комментарий',
        ];
    }
}
