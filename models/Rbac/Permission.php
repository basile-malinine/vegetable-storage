<?php

namespace app\models\Rbac;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class Permission extends Model
{
    public string $name;
    public string $description;

    public function rules()
    {
        return [
            [['name', 'description'], 'trim'],
            [['name', 'description'], 'required', 'message' => 'Необходимо заполнить.'],
            [['name'], 'uniqueName'],
            [['description'], 'uniqueDescription'],
            [['name'], 'string', 'max' => 50],
            ['description', 'string', 'max' => 255],
        ];
    }

    public function uniqueName($attribute, $params)
    {
        $auth = Yii::$app->authManager;
        $testPermission = $auth->getPermission($this->name);
        if ($testPermission) {
            $this->addError('name', 'Уже есть.');
        }
    }

    public function uniqueDescription($attribute, $params)
    {
        $auth = Yii::$app->authManager;
        $permissions = $auth->getPermissions();
        $descriptions = ArrayHelper::getColumn($permissions, 'description');
        $isExist = in_array($this->description, $descriptions);

        if ($isExist) {
            $this->addError('description', 'Уже есть.');
        }
    }

    // Разрешения для роли
    public static function getListByRole($role = null)
    {
        if (empty($role)) {
            return [];
        }

        $auth = Yii::$app->authManager;
        $permissions = $auth->getPermissionsByRole($role);
        $permissions = ArrayHelper::map($permissions, 'name', 'description');
        asort($permissions);

        return $permissions;
    }

    // Доступные разрешения
    public static function getRevertListByRole($role = null)
    {
        $auth = Yii::$app->authManager;
        $permissions = $auth->getPermissions();
        $permissions = ArrayHelper::map($permissions, 'name', 'description');
        $permissionsByRole = [];

        if ($role) {
            $permissionsByRole = $auth->getPermissionsByRole($role);
            $permissionsByRole = ArrayHelper::map($permissionsByRole, 'name', 'description');
        }

        $permissions = array_diff_assoc($permissions, $permissionsByRole);

        asort($permissions);

        return $permissions;
    }
}