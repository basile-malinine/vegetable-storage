<?php

namespace app\models\AcceptanceType;

/**
 * This is the model class for table "acceptance_type".
 *
 * @property int $id ID
 * @property string $name Название
 * @property string|null $comment Комментарий
 */
class AcceptanceType extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'acceptance_type';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'unique'],
            [['name'], 'string', 'max' => 30],
            [['comment'], 'default', 'value' => null],
            [['comment'], 'string', 'max' => 255],
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
