<?php

namespace app\models\PalletType;

use app\models\Base;

/**
 * This is the model class for table "pallet_type".
 *
 * @property int $id
 * @property int $priority
 * @property string $name Название
 * @property string|null $comment Комментарий
 */
class PalletType extends Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pallet_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['priority'], 'integer'],
            [['priority', 'name'], 'required'],
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
            'priority' => 'Приоритет',
            'name' => 'Название',
            'comment' => 'Комментарий',
        ];
    }
}
