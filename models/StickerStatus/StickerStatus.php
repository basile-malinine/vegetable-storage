<?php

namespace app\models\StickerStatus;

use app\models\UpdateGoogle;

/**
 * This is the model class for table "sticker_status".
 *
 * @property int $id
 * @property string $name Название
 * @property string|null $comment Комментарий
 */
class StickerStatus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sticker_status';
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
        $this->updateGoogle();
    }

    public function afterDelete()
    {
        $this->updateGoogle();
    }

    private function updateGoogle(): void
    {
        $ug = new UpdateGoogle('DB!N4:N', $this->getListForGoogle());

        // Для таблицы Test Table Security
        $ug->update('1cr8nsLo9dq-f1n2Tw7rG2sqnS-TtyXoF-G9qfwPRZ4M');

        // Для таблицы Старший смены
        $ug->update('1wzmRAhmt_PQufvNIzAsOvUnHfGCtzLuyx5UuncwdeNc');
    }

    // Список Статусов
    public static function getList(): array
    {
        return self::find()
            ->select(['name', 'id'])
            ->indexBy('id')
            ->orderBy(['name' => SORT_ASC])
            ->column();
    }

    // Формат списка для Google Sheets
    private function getListForGoogle(): array
    {
        return array_values(self::getList());
    }
}
