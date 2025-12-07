<?php

namespace app\models\Rbac;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class Role extends Model
{
    public string $name = '';
    public string $description = '';

    public function rules()
    {
        return [
            [['name', 'description'], 'trim'],
            [['name', 'description'], 'required', 'message' => 'Необходимо заполнить.'],
            [['name'], 'uniqueName'],
            [['description'], 'uniqueDescription'],
            [['name'], 'string', 'max' => 30],
            ['description', 'string', 'max' => 255],
        ];
    }

    public function uniqueName($attribute, $params)
    {
        $auth = Yii::$app->authManager;
        $testRole = $auth->getRole($this->name);
        if (Yii::$app->controller->action->id === 'add-role' && $testRole) {
            $this->addError('name', 'Уже есть.');
        }
    }

    public function uniqueDescription($attribute, $params)
    {
        $auth = Yii::$app->authManager;
        $roles = $auth->getRoles();
        $descriptions = ArrayHelper::getColumn($roles, 'description');
        $isExist = in_array($this->description, $descriptions);

        if (Yii::$app->controller->action->id === 'add-role' && $isExist) {
            $this->addError('description', 'Уже есть.');
        }
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Идентификатор',
            'description' => 'Название',
        ];
    }

    public function save()
    {
        $auth = Yii::$app->authManager;
        if (Yii::$app->controller->action->id === 'add-role') {
            $role = $auth->createRole($this->name);
            $role->description = $this->description;
            $auth->add($role);
        } elseif (Yii::$app->controller->action->id === 'edit-role') {
            $role = $auth->getRole($this->name);
            $role->description = $this->description;
            $auth->update($this->name, $role);
        }

        return true;
    }

    public function delete()
    {
        $auth = Yii::$app->authManager;
        $role = $auth->getRole($this->name);
        $userIds = $auth->getUserIdsByRole($this->name);

        // Удаление роли у пользователей
        foreach ($userIds as $userId) {
            $auth->revoke($role, $userId);
        }

        // Удаление у роли дочерних объектов
        $auth->removeChildren($role);

        // Удаление самой роли
        $auth->remove($role);

        return true;
    }

    public function getUsers()
    {
        $auth = Yii::$app->authManager;
        $role = $auth->getRole($this->name);

        return $auth->getUserIdsByRole($role->name);
    }

    public static function getList(): array
    {
        $auth = Yii::$app->authManager;
        $roles = $auth->getRoles();
        $roles = ArrayHelper::map($roles, 'name', 'description');
        asort($roles);

        return $roles;
    }

    // Получить роли Пользователя
    public static function getListByUser($userId)
    {
        $auth = Yii::$app->authManager;
        $roles = $auth->getRolesByUser($userId);
        $roles = ArrayHelper::map($roles, 'name', 'description');
        asort($roles);

        return $roles;
    }

    // Получить доступные роли для Пользователя
    public static function getRevertListByUser($userId)
    {
        $auth = Yii::$app->authManager;
        // Все роли
        $roles = $auth->getRoles($userId);
        $roles = ArrayHelper::map($roles, 'name', 'description');
        // Роли у пользователя
        $rolesByUser = $auth->getRolesByUser($userId);
        $rolesByUser = ArrayHelper::map($rolesByUser, 'name', 'description');

        // Выбираем незадействованные роли
        $roles = array_diff_assoc($roles, $rolesByUser);
        asort($roles);

        return $roles;
    }

    // Добавить роль Пользователю
    public static function assignRoleToUser($roleName, $userId)
    {
        $auth = Yii::$app->authManager;
        $role = $auth->getRole($roleName);
        $auth->assign($role, $userId);
    }

    // Удалить роль у Пользователя
    public static function revokeRoleFromUser($roleName, $userId)
    {
        $auth = Yii::$app->authManager;
        $role = $auth->getRole($roleName);
        $auth->revoke($role, $userId);
    }
}
