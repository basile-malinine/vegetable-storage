<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var Country $model */
/** @var string $header */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use app\models\Country\Country;

$this->registerCssFile('https://cdn.jsdelivr.net/npm/suggestions-jquery@21.12.0/dist/css/suggestions.min.css');
$this->registerJsFile('https://cdn.jsdelivr.net/npm/suggestions-jquery@21.12.0/dist/js/jquery.suggestions.min.js',
    ['position' => \yii\web\View::POS_END]);
$this->registerJsFile('@web/js/dadata.country-form.js', ['position' => \yii\web\View::POS_END]);

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

        <div class="row form-row">
            <!-- Название -->
            <div class="form-col col-3">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            </div>

            <!-- Код -->
            <div class="form-col col-1">
                <?= $form->field($model, 'alfa2')->textInput(['readonly' => true]) ?>
            </div>

            <!-- Полное название -->
            <div class="form-col col-6">
                <?= $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="row form-row">
            <!-- Название ID для Юр. лиц -->
            <div class="form-col col-2">
                <?= $form->field($model, 'inn_legal_name')->textInput(['maxlength' => true]) ?>
            </div>
            <!-- Ширина ID для Юр. лиц -->
            <div class="form-col col-2">
                <?= $form->field($model, 'inn_legal_size')->textInput() ?>
            </div>
        </div>
        <div class="row form-last-row">
            <!-- Название ID для Физ. лиц -->
            <div class="form-col col-2">
                <?= $form->field($model, 'inn_name')->textInput(['maxlength' => true]) ?>
            </div>
            <!-- Ширина ID для Физ. лиц -->
            <div class="form-col col-2">
                <?= $form->field($model, 'inn_size')->textInput() ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-light btn-outline-primary btn-sm me-2']) ?>
            <?= Html::a('Отмена', '/country/index', ['class' => 'btn btn-light btn-outline-secondary btn-sm']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
