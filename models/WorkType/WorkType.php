<?php

namespace app\models\WorkType;

use app\models\Base;

/**
 * This is the model class for table "work_type".
 *
 * @property int $id ID
 * @property string $name Название
 * @property string|null $comment Комментарий
 */
class WorkType extends Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'work_type';
    }

    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'comment' => 'Комментарий',
        ];
    }

}
