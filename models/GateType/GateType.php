<?php

namespace app\models\GateType;

use yii\db\ActiveRecord;
use app\models\UpdateGoogle;

/**
 * This is the model class for table "gate_type".
 *
 * @property int $id
 * @property string $name Название
 * @property string|null $comment Комментарий
 */
class GateType extends ActiveRecord
{
    public static function tableName()
    {
        return 'gate_type';
    }

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
        $ug = new UpdateGoogle('DB!J4:J', $this->getListForGoogle());

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
