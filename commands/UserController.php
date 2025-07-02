<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use app\models\User\User;

class UserController extends Controller
{
    public $username;
    public $email;
    public $password;

    public function options($action)
    {
        $options = parent::options($action);
        if ($action === 'create') {
            $options = [
                'username',
                'email',
                'password',
            ];
        }

        return $options;
    }

    public function optionAliases()
    {
            return [
                'u' => 'username',
                'e' => 'email',
                'p' => 'password',
            ];
    }

    public function actionIndex()
    {
        $users = User::find()->all();
        foreach ($users as $user) {
            print_r(str_pad($user->id, 3, '0', STR_PAD_LEFT) . ' '
                . str_pad($user->name, 12, ' ') . ' ' . $user->email . PHP_EOL);
        }
    }

    public function actionCreate()
    {
        $user = new User();
        $user->name = $this->username;
        $user->email = $this->email;
        $user->pass_hash = Yii::$app->getSecurity()->generatePasswordHash($this->password);
        $res = $user->save();
        print_r($res ? 'Запись добавлена' : 'Не удалось добавить запись');
    }
}