<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var \app\models\User\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

?>

<div class="page-content">
    <div class="page-top-panel">
        <div class="page-top-panel-header d-inline">
            Вход в систему
        </div>
    </div>
    <div class="page-content-form">
        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'fieldConfig' => [
                'template' => "{label}\n{input}\n{error}",
                'labelOptions' => ['class' => 'col-form-label'],
                'inputOptions' => ['class' => 'form-control form-control-sm'],
                'errorOptions' => ['class' => 'invalid-feedback'],
                'enableClientValidation' => false,
            ],
        ]); ?>

        <div class="row form-row">
            <!-- Адрес электронной почты -->
            <div class="form-col col-4">
                <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>
            </div>
        </div>

        <div class="row form-row">
            <!-- Пароль-->
            <div class="form-col col-4">
                <?= $form->field($model, 'password')->passwordInput() ?>
            </div>
        </div>

        <div class="row form-last-row mt-3">
            <!-- Запомнить-->
            <div class="form-col col-4">
                <?= $form->field($model, 'rememberMe')->checkbox() ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton(
                'Войти',
                [
                    'class' => 'btn btn-light btn-outline-primary btn-sm',
                    'name' => 'login-button',
                    'style' => 'width: 100px'
                ]) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>