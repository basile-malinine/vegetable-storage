<?php

namespace app\models\User;

/**
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $pass_hash
 */

use Yii;
use yii\web\IdentityInterface;

use app\models\Base;

class User extends Base implements IdentityInterface
{
    public string $password = '';

    public static function tableName()
    {
        return 'user';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'email', 'password'], 'string'],
            [['email'], 'email'],
            [['email'], 'unique'],
            [['password'], 'required',
                'when' => function ($model) {
                    return !isset($model->pass_hash);
                }, 'message' => 'Необходимо заполнить Пароль.'
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Имя пользователя',
            'email' => 'Адрес электронной почты',
            'password' => 'Пароль',
        ];
    }

    public function beforeSave($insert)
    {
        if ($insert || $this->password) {
            $this->pass_hash = Yii::$app->getSecurity()->generatePasswordHash($this->password);
        }

        return true;
    }

        public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // return null;
    }

    public static function findByEmail($email)
    {
        $user = self::findOne(['email' => $email]);
        if ($user) {
            return new static($user);
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        // return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        // return $this->authKey === $authKey;
    }

    public function validatePassword($password)
    {
        return \Yii::$app->getSecurity()->validatePassword($password, $this->pass_hash);
    }
}
