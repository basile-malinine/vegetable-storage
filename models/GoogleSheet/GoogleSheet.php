<?php

namespace app\models\GoogleSheet;

/**
 * This is the model class for table "google_sheet".
 *
 * @property int $id
 * @property string $sheet_id Sheet ID
 * @property string $name Название
 * @property string|null $comment Комментарий
 */
class GoogleSheet extends \yii\db\ActiveRecord
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
            'sheet_id' => 'Sheet ID',
            'name' => 'Название',
            'comment' => 'Комментарий',
        ];
    }
}
