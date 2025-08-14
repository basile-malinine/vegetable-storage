<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var \app\models\User\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

?>

<div class="page-content d-flex justify-content-center align-items-center">
    <div class="page-content-form form-login">
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

        <div class="form-login-header">
            Вход
        </div>

        <div class="form-login-body">
            <div class="row form-row">
                <!-- Адрес электронной почты -->
                <div class="form-col col-12">
                    <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>
                </div>
            </div>

            <div class="row form-row">
                <!-- Пароль-->
                <div class="form-col col-12">
                    <?= $form->field($model, 'password')->passwordInput() ?>
                </div>
            </div>

            <div class="row form-row mt-5">
                <div class="form-col col-12 d-flex justify-content-between align-items-center">
                    <!-- Запомнить-->
                    <?= $form->field($model, 'rememberMe')->checkbox() ?>

                    <!-- Войти -->
                    <?= Html::submitButton(
                        'Войти',
                        [
                            'class' => 'btn btn-light btn-outline-primary btn-sm mb-3',
                            'name' => 'login-button',
                            'style' => 'width: 100px'
                        ]) ?>
                </div>
            </div>
        </div>

        <!--        <div class="form-login-bottom">-->
        <!--        </div>-->

        <?php ActiveForm::end(); ?>

    </div>
</div>