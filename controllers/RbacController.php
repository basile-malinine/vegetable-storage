<?php

namespace app\controllers;

use Yii;
use yii\bootstrap5\ActiveForm;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use app\models\Rbac\Permission;
use app\models\Rbac\Role;
use app\models\User\User;

class RbacController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'index',
                            'add-role',
                            'edit-role',
                            'remove-role',
                            'get-permissions-by-role',
                            'add-role-permissions',
                            'remove-role-permissions',
                            'user',
                            'add-role-to-user',
                            'remove-role-from-user',
                        ],
                        'roles' => ['role.access'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $header = 'Настройки ролей';

        return $this->render('form-role', compact('header'));
    }

    public function actionAddRole()
    {
        $model = new Role();
        $header = 'Новая роль';

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                if ($model->validate()) {
                    if ($model->save()) {
                        // Записываем в сессию ID новой роли для передачи в JS
                        $session = Yii::$app->session;
                        $session->remove('rbac.delRole');
                        $session->set('rbac.newRole', $model->name);
                        $this->redirect(['/rbac']);
                    }
                }
            }
        } elseif ($this->request->isAjax) {
            return $this->renderAjax('form-role-add-edit', compact('model', 'header'));
        } else {
            $model->loadDefaultValues();
        }

        return $this->renderAjax('form-role-add-edit', compact('model', 'header'));
    }

    public function actionEditRole($name)
    {
        $auth = Yii::$app->authManager;
        $role = $auth->getRole($name);

        $model = new Role();
        $model->name = $name;
        $model->description = $role->description;

        $header = 'Роль [' . $role->description . ']';

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                if ($model->validate()) {
                    if ($model->save()) {
                        $this->redirect(['/rbac']);
                    }
                }
            }
        } elseif ($this->request->isAjax) {
            return $this->renderAjax('form-role-add-edit', compact('model', 'header'));
        } else {
            $model->loadDefaultValues();
        }

        return $this->renderAjax('form-role-add-edit', compact('model', 'header'));
    }

    public function actionRemoveRole($name)
    {
        $auth = Yii::$app->authManager;
        $role = $auth->getRole($name);

        $model = new Role();
        $model->name = $role->name;
        $model->description = $role->description;

        $users = $model->users;
        if ($users) {
            $message = 'Роль ' . $model->description . ' привязана к пользователям. Всё равно продолжить?';
        } else {
            $message = 'Роль ' . $model->description . ' не используется. Продолжить?';
        }

        $header = 'Удаление роли';

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                if ($model->delete()) {
                    // Удаляем из сессии, чтобы удалить из сессии JS
                    $session = Yii::$app->session;
                    $session->remove('rbac.newRole');
                    $session->set('rbac.delRole', $role->name);
                    $this->redirect(['/rbac']);
                }
            }
        } elseif ($this->request->isAjax) {
            return $this->renderAjax('form-role-remove',
                compact('model', 'header', 'message'));
        } else {
            $model->loadDefaultValues();
        }

        return $this->renderAjax('form-role-remove',
            compact('model', 'header', 'message'));
    }

    /** Получение списков разрешений для роли:
     * $permissionsRole - назначенные
     * $permissionsNotRole - не назначенные
     * Вызов из www/js/rbac-role.js
     */
    public function actionGetPermissionsByRole()
    {
        $name = Yii::$app->request->post('name');
        Yii::$app->response->format = Response::FORMAT_JSON;

        $permissionsRole = Permission::getListByRole($name);
        $permissionsNotRole = Permission::getRevertListByRole($name);

        return [$permissionsRole, $permissionsNotRole];
    }

    // Добавление разрешений для роли
    public function actionAddRolePermissions()
    {
        $role = Yii::$app->request->post('role');
        $permissionNames = Yii::$app->request->post('permissions');
        if (!$role || !$permissionNames) {
            return;
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        $auth = Yii::$app->authManager;
        $role = $auth->getRole($role);

        foreach ($permissionNames as $permissionName) {
            $permission = $auth->getPermission($permissionName);
            $auth->addChild($role, $permission);
        }
    }

    // Удаление разрешений для роли
    public function actionRemoveRolePermissions()
    {
        $role = Yii::$app->request->post('role');
        $permissionNames = Yii::$app->request->post('permissions');
        if (!$role || !$permissionNames) {
            return;
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        $auth = Yii::$app->authManager;
        $role = $auth->getRole($role);

        foreach ($permissionNames as $permissionName) {
            $permission = $auth->getPermission($permissionName);
            $auth->removeChild($role, $permission);
        }
    }

    // Настройки ролей и разрешений для Пользователя --------------------------------------

    public function actionUser($id)
    {
        $model = User::findOne($id);
        $header = 'Настройка доступа для Пользователя ' . $model->name;

        return $this->render('form-user', compact('model', 'header'));
    }

    // Добавление роли Пользователю
    public function actionAddRoleToUser()
    {
        $role = Yii::$app->request->post('role');
        $userId = Yii::$app->request->post('userId');
        Role::assignRoleToUser($role, $userId);
    }

    // Удаление роли у Пользователя
    public function actionRemoveRoleFromUser()
    {
        $role = Yii::$app->request->post('role');
        $userId = Yii::$app->request->post('userId');
        Role::revokeRoleFromUser($role, $userId);
    }
}