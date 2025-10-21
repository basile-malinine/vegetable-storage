<?php

/** @var User $model */

/** @var array $header */

use yii\bootstrap5\Html;
use app\models\Rbac\Role;
use app\models\User\User;

$this->registerJsFile('@web/js/rbac-user.js');
$this->registerJs('const userId = ' . $model->id, \yii\web\View::POS_HEAD);
?>

<div class="page-top-panel">
    <div class="page-top-panel-header d-flex">
        <?php
        echo '<a href="/user/edit/' . $model->id . '"'
        . ' class="btn btn-return btn-light btn-outline-secondary btn-sm mt-1 me-3 pe-2"><i class="fa fa-arrow-left"></i>'
        . '</a>';
        ?>
        <?= $header ?>
    </div>
</div>

<div class="page-content">
    <div class="page-content-form">

        <div class="row form-row">
            <!-- Назначенные роли -->
            <div class="form-col col-4">
                <?= Html::label('Назначенные роли', 'userRoles', ['class' => 'col-form-label']) ?>
                <?= Html::listBox('userRoles', null, Role::getListByUser($model->id),
                    [
                        'id' => 'userRoles',
                        'class' => 'form-control form-control-sm',
                        'size' => 7,
                    ]
                ) ?>
            </div>

            <!-- Кнопки Добавить / Удалить роль -->
            <div class="form-col col-1 d-flex flex-column justify-content-center">
                <div class="d-flex flex-column">
                    <?= Html::a('<i class="fa fa-arrow-left me-2"></i><span>Добавить</span>', null,
                        [
                            'id' => 'addRole',
                            'class' => 'btn btn-light btn-outline-secondary btn-sm mt-2 ps-2 pt-1 pb-1 align-self-center',
                            'style' => 'height: 32px; width: 100px',
                        ])
                    ?>
                    <?= Html::a('<span>Удалить</span><i class="fa fa-arrow-right ms-2"></i>', null,
                        [
                            'id' => 'removeRole',
                            'class' => 'btn btn-light btn-outline-secondary btn-sm mt-2 ps-2 pt-1 pb-1 align-self-center',
                            'style' => 'height: 32px; width: 100px',
                        ])
                    ?>
                </div>
            </div>

            <!-- Доступные роли -->
            <div class="form-col col-4">
                <?= Html::label('Доступные роли', 'roles', ['class' => 'col-form-label']) ?>
                <?= Html::listBox('roles', null, Role::getRevertListByUser($model->id),
                    [
                        'id' => 'roles',
                        'class' => 'form-control form-control-sm',
                        'size' => 7,
                    ]
                ) ?>
            </div>
        </div>
    </div>
</div>