<?php

namespace app\models\AcceptanceStatus;

use app\models\GoogleBase;

/**
 * This is the model class for table "acceptance_status".
 *
 * @property int $id
 * @property string $name Название
 * @property string|null $comment Комментарий
 */
class AcceptanceStatus extends GoogleBase
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'acceptance_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 50],
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
