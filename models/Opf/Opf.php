<?php

namespace app\models\Opf;

use app\models\Base;

/**
 * This is the model class for table "opf".
 *
 * @property int $id
 * @property string $name Название
 * @property string|null $comment Комментарий
 */
class Opf extends Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'opf';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 20],
            [['name'], 'unique'],
            [['comment'], 'default', 'value' => null],
            [['comment'], 'string'],
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
