<?php

namespace app\models\SystemObject;

use yii\db\ActiveRecord;

use app\models\GoogleSheet\GoogleSheet;
use app\models\SystemObjectGoogleSheet\SystemObjectGoogleSheet;

/**
 * This is the model class for table "system_object".
 *
 * @property int $id
 * @property string $table_name Имя таблицы объекта
 * @property string $name Название объекта
 * @property int $is_google Поддержка Google
 * @property string|null $comment Комментарий
 *
 * @property GoogleSheet[] $googleSheets
 * @property SystemObjectGoogleSheet[] $systemObjectGoogleSheets
 */
class SystemObject extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'system_object';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['table_name', 'name', 'is_google'], 'required'],
            [['table_name', 'name'], 'string', 'max' => 50],
            [['table_name'], 'unique'],
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
            'table_name' => 'Таблица в БД',
            'name' => 'Название',
            'is_google' => 'Поддержка Google',
            'comment' => 'Комментарий',
        ];
    }

    /**
     * Gets query for [[GoogleSheets]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGoogleSheets()
    {
        return $this->hasMany(GoogleSheet::class, ['id' => 'google_sheet_id'])
            ->viaTable('system_object_google_sheet', ['system_object_id' => 'id']);
    }

    /**
     * Gets query for [[SystemObjectGoogleSheets]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSystemObjectGoogleSheets()
    {
        return $this->hasMany(SystemObjectGoogleSheet::class, ['system_object_id' => 'id']);
    }

    public static function getList()
    {
        return self::find()
            ->select(['name', 'id'])
            ->indexBy('id')
            ->orderBy(['name' => SORT_ASC])
            ->column();
    }

    // Список таблиц БД
    public static function getDbTableList(): array
    {
        $tables = \Yii::$app->db->schema->tableNames;
        $tables = array_combine($tables, $tables);

        return $tables;
    }
}
