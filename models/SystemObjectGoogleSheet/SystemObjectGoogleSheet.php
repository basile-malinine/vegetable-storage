<?php

namespace app\models\SystemObjectGoogleSheet;

use app\models\Base;
use app\models\GoogleSheet\GoogleSheet;
use app\models\SystemObject\SystemObject;

/**
 * This is the model class for table "system_object_google_sheet".
 *
 * @property int $system_object_id
 * @property int $google_sheet_id
 * @property string|null $google_sheet_range Диапазон для таблицы Google
 * @property string|null $comment Комментарий
 *
 * @property GoogleSheet $googleSheet
 * @property SystemObject $systemObject
 */
class SystemObjectGoogleSheet extends Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'system_object_google_sheet';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['google_sheet_range', 'comment'], 'default', 'value' => null],
            [['system_object_id', 'google_sheet_id'], 'required'],
            [['system_object_id', 'google_sheet_id'], 'integer'],
            [['comment'], 'string'],
            [['google_sheet_range'], 'string', 'max' => 50],
            [['system_object_id', 'google_sheet_id'], 'unique', 'targetAttribute' => ['system_object_id', 'google_sheet_id']],
            [['google_sheet_id'], 'exist', 'skipOnError' => true, 'targetClass' => GoogleSheet::class, 'targetAttribute' => ['google_sheet_id' => 'id']],
            [['system_object_id'], 'exist', 'skipOnError' => true, 'targetClass' => SystemObject::class, 'targetAttribute' => ['system_object_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'system_object_id' => 'Объект в системе',
            'google_sheet_id' => 'Таблица в Google',
            'google_sheet_range' => 'Диапазон в таблице',
            'comment' => 'Комментарий',
        ];
    }

    /**
     * Gets query for [[GoogleSheet]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGoogleSheet()
    {
        return $this->hasOne(GoogleSheet::class, ['id' => 'google_sheet_id']);
    }

    /**
     * Gets query for [[SystemObject]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSystemObject()
    {
        return $this->hasOne(SystemObject::class, ['id' => 'system_object_id']);
    }

}
