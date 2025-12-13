<?php

namespace app\models\PaymentMethod;

use app\models\Base;

/**
 * This is the model class for table "payment_method".
 *
 * @property int $id
 * @property string $name Название
 * @property string|null $comment Комментарий
 */
class PaymentMethod extends Base
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment_method';
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
            'name' => 'Name',
            'comment' => 'Comment',
        ];
    }
}
