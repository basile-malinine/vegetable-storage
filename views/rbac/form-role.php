<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var array $roles */

/** @var array $header */

use yii\bootstrap5\Html;
use app\models\Rbac\Permission;
use app\models\Rbac\Role;

// Если добавлена новая роль записываем ID в сессию браузера
$session = Yii::$app->session;
$nameNewRole = $session->get('rbac.newRole');
if ($nameNewRole) {
    $this->registerJs('sessionStorage.setItem("rbac.currRole", "' . $nameNewRole . '");',
        \yii\web\View::POS_HEAD);
    $session->remove('rbac.newRole');
}

$this->registerJsFile('@web/js/rbac-role.js');
?>

<div class="page-top-panel">
    <div class="page-top-panel-header d-inline">
        <?= $header ?>
    </div>
</div>

<div class="page-content">
    <div class="page-content-form">

        <div class="row form-row">
            <!-- Список ролей -->
            <div class="form-col col-3">
                <div class="d-flex justify-content-between">
                    <?= Html::label('Роли', 'roles', ['class' => 'col-form-label']) ?>
                    <div>
                        <?= Html::a('<i class="fa fa-plus"></i>', null,
                            [
                                'id' => 'addRole',
                                'class' => 'btn btn-light btn-outline-secondary btn-sm mt-2 ps-2 pt-0 pb-3',
                                'style' => 'height: 24px; width: 29px',
                            ])
                        ?>
                        <?= Html::a('<i class="fa fa-minus"></i>', null,
                            [
                                'id' => 'removeRole',
                                'class' => 'btn btn-light btn-outline-secondary btn-sm mt-2 ps-2 pt-0 pb-3',
                                'style' => 'height: 24px; width: 29px',
                            ])
                        ?>
                    </div>
                </div>
                <?= Html::listBox('roles', null, Role::getList(),
                    [
                        'id' => 'roles',
                        'class' => 'form-control form-control-sm',
                        'size' => 7,
                    ]
                ) ?>
            </div>

            <!-- Список разрешений для роли -->
            <div class="form-col col-4">
                <?= Html::label('Разрешения для роли', 'role-permissions', ['class' => 'col-form-label']) ?>
                <?= Html::listBox('role-permissions', null, [],
                    [
                        'id' => 'role-permissions',
                        'class' => 'form-control form-control-sm',
                        'size' => 29,
                        'multiple' => true,
                    ]
                ) ?>
            </div>

            <!-- Кнопки Добавить / Удалить разрешения для роли -->
            <div class="form-col col-1 d-flex flex-column justify-content-center">
                <div class="d-flex flex-column">
                    <?= Html::a('<i class="fa fa-arrow-left me-2"></i><span>Добавить</span>', null,
                        [
                            'id' => 'addPermissions',
                            'class' => 'btn btn-light btn-outline-secondary btn-sm mt-2 ps-2 pt-1 pb-1 align-self-center',
                            'style' => 'height: 32px; width: 100px',
                        ])
                    ?>
                    <?= Html::a('<span>Удалить</span><i class="fa fa-arrow-right ms-2"></i>', null,
                        [
                            'id' => 'removePermissions',
                            'class' => 'btn btn-light btn-outline-secondary btn-sm mt-2 ps-2 pt-1 pb-1 align-self-center',
                            'style' => 'height: 32px; width: 100px',
                        ])
                    ?>
                </div>
            </div>

            <!-- Список доступных разрешений -->
            <div class="form-col col-4">
                <?= Html::label('Доступные разрешения', 'permissions', ['class' => 'col-form-label']) ?>
                <?= Html::listBox('permissions', null, [],
                    [
                        'id' => 'permissions',
                        'class' => 'form-control form-control-sm',
                        'size' => 29,
                        'multiple' => true,
                    ]
                ) ?>
            </div>
        </div>

    </div>
</div>
