<?php

namespace app\models\Currency;

use app\models\Base;

/**
 * This is the model class for table "currency".
 *
 * @property int $id
 * @property string $name Название
 * @property string|null $comment Комментарий
 */
class Currency extends Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'currency';
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
