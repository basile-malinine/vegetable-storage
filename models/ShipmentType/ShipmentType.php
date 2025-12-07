<?php

namespace app\models\ShipmentType;

use app\models\Base;

/**
 * This is the model class for table "shipment_type".
 *
 * @property int $id ID
 * @property string $name Название
 * @property string|null $comment Комментарий
 */
class ShipmentType extends Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'shipment_type';
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
