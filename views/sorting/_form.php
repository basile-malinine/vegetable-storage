<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var yii\data\ActiveDataProvider $dataProviderItem */
/** @var Sorting $model */

/** @var string $header */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use kartik\date\DatePicker;
use kartik\select2\Select2;

use app\models\Documents\Remainder\Remainder;
use app\models\Documents\Sorting\Sorting;

$actionID = Yii::$app->controller->action->id;

// Доступность кнопки Сохранить
$allowBtnSave = false;
// Если нет приёмки, кнопка доступна
if (!$model->acceptance) {
    $allowBtnSave = true;
}
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
            <!-- Дата -->
            <div class="form-col col-2">
                <?= $form->field($model, 'date')->widget(DatePicker::class, [
                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                    'readonly' => true,
                    'disabled' => (bool)$model->date_close,
                    'size' => 'sm',

                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'dd.mm.yyyy',
                        'todayHighlight' => true,
                    ],
                ]) ?>
            </div>

            <!-- Приёмка -->
            <div hidden>
                <?= $form->field($model, 'acceptance_id')->textInput([
                    'id' => 'hidden-acceptance_id',
                ]) ?>
            </div>
            <div class="form-col col-6" <?= $actionID === 'edit' ? "hidden" : "" ?>>
                <?= $form->field($model, 'acceptance_id')->widget(Select2::class, [
                    'data' => Remainder::getListForMoving(),
                    'options' => [
                        'id' => 'acceptance-id',
                        'placeholder' => 'Не назначена',
                    ],
                ]); ?>
            </div>
        </div>

        <!-- GridView (Состав) -->
        <div class="row form-row">
            <div class="form-col col-10">
                <?= isset($dataProviderItem) ? $this->render('_item_grid', compact(['model', 'dataProviderItem'])) : '' ?>
            </div>
            <div style="margin-top: -15px">
                <?= $form->field($model, 'error')->input('text', [
                    'style' => 'display: none;',
                ])->label(false) ?>
            </div>
        </div>

        <!-- Комментарий -->
        <div <?= $actionID === 'create' ? 'class="row form-last-row mt-2"' : 'class="row form-last-row"' ?>>
            <div class="form-col col-10">
                <?= $form->field($model, 'comment')->textarea() ?>
            </div>
        </div>

        <div class="form-group">
            <?php if ($allowBtnSave || $model->items[0]->isChanges() || $model->items[0]->quantity < 0.1): ?>
                <?= Html::submitButton('Сохранить', [
                    'class' => 'btn btn-light btn-outline-primary btn-sm me-2'
                ]) ?>
            <?php elseif (!$model->date_close || $model->items[0]->isChanges() && $model->items[0]->quantity > 0): ?>
                <?= Html::a('Провести', '/sorting/apply', [
                    'id' => 'btn-change-close',
                    'class' => 'btn btn-light btn-outline-secondary btn-sm',
                    'data' => [
                        'method' => 'post',
                        'params' => [
                            'id' => $model->id,
                        ],
                    ],
                ]) ?>
            <?php elseif ($model->date_close): ?>
                <?= Html::a('Снять с остатка', '/sorting/cancel', [
                    'id' => 'btn-change-close',
                    'class' => 'btn btn-light btn-outline-secondary btn-sm',
                    'data' => [
                        'method' => 'post',
                        'params' => [
                            'id' => $model->id,
                        ],
                    ],
                ]) ?>
            <?php endif; ?>
            <?= Html::a('К списку', '/sorting', ['class' => 'btn btn-light btn-outline-secondary btn-sm']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
