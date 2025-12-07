<?php

namespace app\models\TemperatureRegime;

use app\models\Base;

/**
 * This is the model class for table "temperature_regime".
 *
 * @property int $id
 * @property string $name Название
 * @property string|null $comment Комментарий
 */
class TemperatureRegime extends Base
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'temperature_regime';
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
