<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var Manager $model */

/** @var string $header */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

use app\models\Manager\Manager;

?>

<div class="page-top-panel">
    <div class="page-top-panel-header d-inline">
        <?= $header ?>
    </div>
</div>

<div class="page-content">
    <div class="page-content-form">

        <?php $form = ActiveForm::begin([
            'id' => 'page-content-form',
            'fieldConfig' => [
                'template' => "{label}\n{input}\n{error}",
                'labelOptions' => ['class' => 'col-form-label pt-0'],
                'inputOptions' => ['class' => 'form-control form-control-sm'],
                'errorOptions' => ['class' => 'invalid-feedback'],
                'enableClientValidation' => false,
            ],
        ]); ?>

        <!-- Имя -->
        <div class="row form-row">
            <div class="form-col col-4">
                <?= $form->field($model, 'name')->textInput(
                    [
                        'maxlength' => true,
                    ]
                ) ?>
            </div>
        </div>


        <div class="row form-row">
            <div class="form-col col-4">
                <div class="card mt-2 mb-1">
                    <div class="card-header pt-1 pb-1 ps-2">Типы</div>
                    <div class="card-body pb-2">
                        <?= $form->field($model, 'is_purchasing_mng')->checkbox() ?>
                        <?= $form->field($model, 'is_sales_mng')->checkbox() ?>
                        <?= $form->field($model, 'is_support')->checkbox() ?>
                        <?= $form->field($model, 'is_purchasing_agent')->checkbox() ?>
                        <?= $form->field($model, 'is_sales_agent')->checkbox() ?>
                    </div>
                </div>
                <?= $form->field($model, 'error')->input('text', [
                    'style' => 'display: none;',
                ])->label(false) ?>
            </div>
        </div>


        <!-- Комментарий -->
        <div class="row form-last-row">
            <div class="form-col col-4">
                <?= $form->field($model, 'comment')->textarea() ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-light btn-outline-primary btn-sm me-2']) ?>
            <?= Html::a('Отмена', '/manager/index', ['class' => 'btn btn-light btn-outline-secondary btn-sm']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
