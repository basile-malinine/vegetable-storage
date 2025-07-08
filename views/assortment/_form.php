<?php

/** @var yii\web\View $this */
/** @var ActiveForm $form */
/** @var Assortment $model */

/** @var string $header */

use kartik\select2\Select2;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use app\models\Assortment\Assortment;
use app\models\Product\Product;
use app\models\Unit\Unit;

// 'create' или 'edit'
$action = Yii::$app->controller->action->id;
// Список Ед. изм. для формы
$unitList = Unit::getList();
// Вычисляем значение по умолчанию в списке
$unitDefault = array_keys($unitList)[0];
// Вес у Ед. изм. по умолчанию (если весовая, должен быть вес)
$weightDefault = Unit::findOne($unitDefault)->weight;
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
            <!-- Наименование -->
            <div class="form-col col-6">
                <?= $form->field($model, 'name')->textInput(
                    [
                        'maxlength' => true,
                    ]
                ) ?>
            </div>
        </div>

        <div class="row form-row">
            <!-- Ед. изм. -->
            <div class="form-col col-2">
                <?= $form->field($model, 'unit_id')->dropDownList(Unit::getList(), [
                    'onchange' => '
                        let weights = ' . json_encode(
                            Unit::find()
                                ->select(['weight'])
                                ->where('is_weight')
                                ->indexBy('id')
                                ->column()
                        ) . ';
                        let weight = weights[this.value];
                        let isWeight = Boolean(weights[this.value]);
                        $("#assortment-weight").val(weight);
                        $("#assortment-weight").attr("readonly", isWeight);
                    ',
                ]) ?>
            </div>

            <!-- Вес -->
            <div class="form-col col-2">
                <?= $form->field($model, 'weight')->textInput([
                    'readonly' => $action === 'create' && count($model->errors) === 0
                        ? (bool)$weightDefault
                        : (bool)$model->unit->is_weight,
                    'value' => $action === 'create' && count($model->errors) === 0
                        ? $weightDefault
                        : $model->unit->weight,
                ]) ?>
            </div>
        </div>

        <div class="row form-row">
            <!-- Базовый продукт -->
            <div class="form-col col-6">
                <?= $form->field($model, 'product_id')->widget(Select2::class, [
                    'data' => Product::getList(),
                    'options' => [
                        'placeholder' => 'Не назначен',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]); ?>
            </div>
        </div>

        <div class="row form-last-row">
            <!-- Комментарий -->
            <div class="form-col col-6">
                <?= $form->field($model, 'comment')->textarea() ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-light btn-outline-primary btn-sm me-2']) ?>
            <?= Html::a('Отмена', '/assortment/index', ['class' => 'btn btn-light btn-outline-secondary btn-sm']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
