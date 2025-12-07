<?php

namespace app\models\DriverStatus;

use app\models\GoogleBase;

/**
 * This is the model class for table "driver_status".
 *
 * @property int $id
 * @property string $name Название
 * @property string|null $comment Комментарий
 */
class DriverStatus extends GoogleBase
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'driver_status';
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

    public function afterSave($insert, $changedAttributes)
    {
        self::updateGoogleSheet($this);
    }

    public function afterDelete()
    {
        self::updateGoogleSheet($this);
    }
}
