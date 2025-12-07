<?php

namespace app\models\CarBrand;

use app\models\Base;

/**
 * This is the model class for table "car_brand".
 *
 * @property int $id
 * @property string $name Название
 * @property string|null $comment Комментарий
 */
class CarBrand extends Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'car_brand';
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
}
