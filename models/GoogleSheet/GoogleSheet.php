<?php

namespace app\models\GoogleSheet;

use app\models\GoogleBase;
use app\models\SystemObject\SystemObject;
use app\models\SystemObjectGoogleSheet\SystemObjectGoogleSheet;

/**
 * This is the model class for table "google_sheet".
 *
 * @property int $id
 * @property string $sheet_id Sheet ID
 * @property string $name Название
 * @property string|null $comment Комментарий
 */
class GoogleSheet extends GoogleBase
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'google_sheet';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sheet_id', 'name'], 'required'],
            [['sheet_id'], 'string', 'max' => 60],
            [['name'], 'string', 'max' => 120],
            [['sheet_id'], 'unique'],
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
            'sheet_id' => 'Таблица Google',
            'name' => 'Название',
            'comment' => 'Комментарий',
        ];
    }

    /**
     * Gets query for [[SystemObjectGoogleSheets]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSystemObjectGoogleSheets()
    {
        return $this->hasMany(SystemObjectGoogleSheet::class, ['google_sheet_id' => 'id']);
    }

    /**
     * Gets query for [[SystemObjects]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSystemObjects()
    {
        return $this->hasMany(SystemObject::class, ['id' => 'system_object_id'])
            ->viaTable('system_object_google_sheet', ['google_sheet_id' => 'id']);
    }
}
