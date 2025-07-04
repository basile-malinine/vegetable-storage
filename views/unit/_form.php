<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var Unit $model */

/** @var string $header */

use kartik\select2\Select2;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use app\models\Unit\Unit;

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

        <div class="row form-last-row">
            <!-- Наименование -->
            <div class="form-col col-4">
                <?= $form->field($model, 'name')->textInput(
                    [
                        'maxlength' => true,
                    ]
                ) ?>
            </div>

            <!-- Весовая -->
            <div class="form-col col-1">
                <?= $form->field($model, 'is_weight')->widget(Select2::class, [
                    'data' => [0 => 'Нет', 1 => 'Да'],
                    'hideSearch' => true,
                    'options' => [
                        'onchange' => '
                            if ($(this).val() == 1) {
                                $("#unit-weight").attr("readonly", false);
                            } else {
                                $("#unit-weight").val("");
                                $("#unit-weight").attr("readonly", true);
                            }',
                    ]
                ]); ?>
            </div>

            <!-- Вес -->
            <div class="form-col col-2">
                <?= $form->field($model, 'weight')->textInput([
                    'readonly' => !(bool)$model->is_weight,
                ]) ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-light btn-outline-primary btn-sm me-2']) ?>
            <?= Html::a('Отмена', '/unit/index', ['class' => 'btn btn-light btn-outline-secondary btn-sm']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
