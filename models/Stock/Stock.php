<?php

namespace app\models\Stock;

use yii\db\ActiveRecord;
use app\models\UpdateGoogle;

/**
 * This is the model class for table "stock".
 *
 * @property int $id ID
 * @property string $name Название
 * @property string|null $address Адрес
 * @property string|null $comment Комментарий
 *
 */
class Stock extends ActiveRecord
{
    public static function tableName()
    {
        return 'stock';
    }

    public function rules()
    {
        return [
            [['address', 'comment'], 'default', 'value' => null],
            [['name'], 'required'],
            [['comment'], 'string'],
            [['name'], 'string', 'max' => 30],
            [['address'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'address' => 'Адрес',
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
        $ug = new UpdateGoogle('DB!C4:C', $this->getListForGoogle());

        // Для таблицы Test Table Security
        $ug->update('1cr8nsLo9dq-f1n2Tw7rG2sqnS-TtyXoF-G9qfwPRZ4M');

        // Для таблицы Старший смены
        $ug->update('1wzmRAhmt_PQufvNIzAsOvUnHfGCtzLuyx5UuncwdeNc');
    }

    // Список Складов
    public static function getList(): array
    {
        return self::find()
            ->select(['name', 'id'])
            ->indexBy('id')
            ->orderBy(['name' => SORT_ASC])
            ->column();
    }

    private function getListForGoogle(): array
    {
        return array_values(self::getList());
    }
}
