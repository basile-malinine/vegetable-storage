<?php

namespace app\models\FlightType;

use app\models\GoogleBase;

/**
 * This is the model class for table "flight_type".
 *
 * @property int $id
 * @property string $name Название
 * @property string|null $comment Комментарий
 */
class FlightType extends GoogleBase
{
    public static function tableName()
    {
        return 'flight_type';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 30],
            [['name'], 'unique'],
            [['comment'], 'string'],
            [['comment'], 'default', 'value' => null],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'comment' => 'Комментарий',
        ];
    }
}
