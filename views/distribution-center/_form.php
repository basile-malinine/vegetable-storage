<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var DistributionCenter $model */

/** @var string $header */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\web\JsExpression;
use kartik\select2\Select2;

use app\models\DistributionCenter\DistributionCenter;
use app\models\LegalSubject\LegalSubject;

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

        <!-- Владелец -->
        <div class="row form-row">
            <div class="form-col col-4">
                <?= $form->field($model, 'legal_subject_id')->widget(Select2::class,
                    [
                        'data' => LegalSubject::getList('is_own OR is_buyer'),
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

        <!-- Название -->
        <div class="row form-row">
            <div class="form-col col-4">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
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
            <?= Html::a('Отмена', '/distribution-center/index', ['class' => 'btn btn-light btn-outline-secondary btn-sm']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
