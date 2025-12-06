<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var SystemObject $model */

/** @var string $header */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\web\JsExpression;
use kartik\select2\Select2;

use app\models\SystemObject\SystemObject;

$this->registerJsFile('@web/js/select2-helper.js', ['position' => \yii\web\View::POS_HEAD]);
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

        <!-- Наименование -->
        <div class="row form-row">
            <div class="form-col col-4">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            </div>
        </div>

        <!-- Таблица в БД -->
        <div class="row form-row">
            <div class="form-col col-4">
                <?= $form->field($model, 'table_name')->widget(Select2::class,
                    [
                        'data' => SystemObject::getDbTableList(),
                        'options' => [
                            'placeholder' => 'Не назначена',
                        ],
                        'pluginOptions' => [
                            'matcher' => new JsExpression('matchStart'),
                        ],
                    ]
                ) ?>
            </div>
        </div>

        <!-- Комментарий -->
        <div class="row form-last-row">
            <div class="form-col col-4">
                <?= $form->field($model, 'comment')->textarea() ?>
            </div>
        </div>

        <!-- Поддержка Google -->
<!--        <div class="row form-last-row">-->
<!--            <div class="form-col col-4 d-flex justify-content-end pt-2">-->
<!--                --><?php //= $form->field($model, 'is_google')->checkbox() ?>
<!--            </div>-->
<!--        </div>-->

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-light btn-outline-primary btn-sm me-2']) ?>
            <?= Html::a('Отмена', '/system-object/index', ['class' => 'btn btn-light btn-outline-secondary btn-sm']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
