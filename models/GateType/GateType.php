<?php

namespace app\models\GateType;

use app\models\GoogleBase;

/**
 * This is the model class for table "gate_type".
 *
 * @property int $id
 * @property string $name Название
 * @property string|null $comment Комментарий
 */
class GateType extends GoogleBase
{
    public static function tableName()
    {
        return 'gate_type';
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

    public function afterSave($insert, $changedAttributes)
    {
        self::updateGoogleSheet($this);
    }

    public function afterDelete()
    {
        self::updateGoogleSheet($this);
    }
}
