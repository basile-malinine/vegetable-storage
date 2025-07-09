<?php

namespace app\models\Country;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "country".
 *
 * @property int $id
 * @property string|null $alfa2 Код
 * @property string $name Название
 * @property string $full_name Полное название
 * @property string $inn_legal_name Название ID для Юр. лица
 * @property int $inn_legal_size Ширина ID для Юр. лица
 * @property string $inn_name Название ID для Физ. лица
 * @property int $inn_size Ширина ID для Физ. лица
 *
 */

class Country extends ActiveRecord
{
    public static function tableName()
    {
        return 'country';
    }

    public function rules()
    {
        return [
            [['alfa2'], 'string'],
            [['name'], 'string', 'max' => 30],
            [['full_name'], 'string', 'max' => 360],
            [['name', 'full_name'], 'trim'],
            [['name', 'full_name'], 'unique'],
            [['inn_name', 'inn_legal_name'], 'string', 'max' => 10],
            [['inn_name', 'inn_legal_name'], 'trim'],
            [['inn_size', 'inn_legal_size'], 'integer'],
            [
                [
                    'name', 'full_name',
                    'inn_name', 'inn_legal_name',
                    'inn_size', 'inn_legal_size',
                ], 'required'
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'alfa2' => 'Код',
            'name' => 'Название',
            'full_name' => 'Полное название',
            'inn_legal_name' => 'Название ID Юр. лица',
            'inn_legal_size' => 'Размер ID Юр. лица',
            'inn_name' => 'Название ID Физ. лица',
            'inn_size' => 'Размер ID Физ. лица',
        ];
    }

    public static function getList(): array
    {
        return self::find()
            ->select(['name', 'id'])
            ->indexBy('id')
            ->orderBy(['name' => SORT_ASC])
            ->column();
    }
}
