<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var User $model */

/** @var string $header */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use app\models\User\User;

?>

<div class="page-top-panel">
    <div class="page-top-panel-header d-flex">
        <?= $header ?>
        <?php if (Yii::$app->controller->action->id !== 'create') : ?>
        <a href="/rbac/user/<?= $model->id ?>" class="btn btn-light btn-outline-secondary btn-sm mt-1 ms-auto pe-3">
            <i class="fa fa-shield-alt"></i>
            <span class="ms-2">Настройка доступа</span>
        </a>
        <?php endif; ?>
    </div>
</div>

<div class="page-content">
    <div class="page-content-form">

        <?php $form = ActiveForm::begin(['id' => 'page-content-form',
        'fieldConfig' => ['template' => "{label}\n{input}\n{error}",
        'labelOptions' => ['class' => 'col-form-label pt-0'],
        'inputOptions' => ['class' => 'form-control form-control-sm'],
        'errorOptions' => ['class' => 'invalid-feedback'],
        'enableClientValidation' => false,],]); ?>

        <div class="row form-row">
            <!-- Имя пользователя -->
            <div class="form-col col-6">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            </div>
        </div>

        <div class="row form-last-row">
            <!-- Адрес электронной почты -->
            <div class="form-col col-3">
                <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
            </div>

            <!-- Пароль -->
            <div class="form-col col-3">
                <?= $form->field($model, 'password')->textInput(['maxlength' => true]) ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-light btn-outline-primary btn-sm me-2']) ?>
            <?= Html::a('Отмена', '/user/index', ['class' => 'btn btn-light btn-outline-secondary btn-sm']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
