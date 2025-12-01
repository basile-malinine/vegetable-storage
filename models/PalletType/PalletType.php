<?php

namespace app\models\PalletType;

use Yii;

/**
 * This is the model class for table "pallet_type".
 *
 * @property int $id
 * @property string $name Название
 * @property string|null $comment Комментарий
 */
class PalletType extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pallet_type';
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
