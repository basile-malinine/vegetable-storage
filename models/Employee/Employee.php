<?php

namespace app\models\Employee;

use DateTime;

use Yii;
use app\models\Base;

/**
 * This is the model class for table "employee".
 *
 * @property int $id ID
 * @property string $surname Фамилия
 * @property string $name Имя
 * @property string|null $last_name Отчество
 * @property string|null $phone Телефон
 * @property string|null $email Адрес электронной почты
 * @property int $created_by Создатель
 * @property string $created_at Время создания
 * @property string $updated_at Время обновления
 * @property string|null $comment Комментарий
 */
class Employee extends Base
{
    public mixed $full_name = null;

    public static function tableName()
    {
        return 'employee';
    }

    public function rules()
    {
        return [
            [['last_name', 'phone', 'email', 'comment'], 'default', 'value' => null],
            [['surname', 'name'], 'required'],
            [['created_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['comment'], 'string'],
            [['surname', 'name', 'last_name'], 'string', 'max' => 20],
            [['phone'], 'string', 'max' => 10],
            [['email'], 'string', 'max' => 50],
            [['email'], 'email'],
            [['phone'], 'unique'],
            [['email'], 'unique'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'surname' => 'Фамилия',
            'name' => 'Имя',
            'last_name' => 'Отчество',
            'full_name' => 'ФИО',
            'phone' => 'Телефон',
            'email' => 'Адрес электронной почты',
            'created_by' => 'Создатель',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
            'comment' => 'Комментарий',
        ];
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->full_name = $this->surname . ' ' . $this->name . ' ' . $this->last_name;
    }

    public function beforeSave($insert)
    {
        $now = (new DateTime('now'))->format('Y-m-d');
        if ($insert) {
            $this->created_by = Yii::$app->user->id;
            $this->created_at = $now;
        }
        $this->updated_at = $now;

        return true;
    }
}
