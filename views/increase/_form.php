<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var yii\data\ActiveDataProvider $dataProviderItem */
/** @var Increase $model */

/** @var string $header */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use kartik\date\DatePicker;
use kartik\select2\Select2;

use app\models\Documents\Increase\Increase;
use app\models\Documents\Remainder\Remainder;
use app\models\LegalSubject\LegalSubject;
use app\models\Stock\Stock;

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
            <!-- Дата оприходования -->
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

            <!-- Тип оприходования -->
            <div hidden>
                <?= $form->field($model, 'type_id')->textInput([
                    'id' => 'hidden-type-id',
                ]) ?>
            </div>
            <div class="form-col col-2">
                <?= $form->field($model, 'type_id')->widget(Select2::class, [
                    'data' => Increase::TYPE_LIST,
                    'options' => [
                        'id' => 'type-id',
                        'disabled' => !$allowBtnSave,
                    ],
                ]); ?>
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

        <div class="row form-row">
            <!-- Предприятие -->
            <div hidden>
                <?= $form->field($model, 'company_own_id')->textInput([
                    'id' => 'hidden-company-own-id',
                ]) ?>
            </div>
            <div class="form-col col-4">
                <?= $form->field($model, 'company_own_id')->widget(Select2::class, [
                    'data' => LegalSubject::getList('is_own = true'),
                    'options' => [
                        'id' => 'company-own-id',
                        'placeholder' => 'Не назначено',
                        'disabled' => true,
                    ],
                ]); ?>
            </div>

            <!-- Склад -->
            <div hidden>
                <?= $form->field($model, 'stock_id')->textInput([
                    'id' => 'hidden-stock-id',
                ]) ?>
            </div>
            <div class="form-col col-2">
                <?= $form->field($model, 'stock_id')->widget(Select2::class, [
                    'data' => Stock::getList(),
                    'options' => [
                        'id' => 'stock-id',
                        'placeholder' => 'Не назначено',
                        'disabled' => true,
                    ],
                ]); ?>
            </div>
        </div>

        <!-- GridView (Состав) -->
        <div class="row form-row">
            <div class="form-col col-10">
                <?= isset($dataProviderItem) ? $this->render('_item_grid', compact(['model', 'dataProviderItem'])) : '' ?>
            </div>
        </div>

        <!-- Комментарий -->
        <div <?= $actionID === 'create' ? 'class="row form-last-row mt-2"' : 'class="row form-last-row"' ?>>
            <div class="form-col col-10">
                <?= $form->field($model, 'comment')->textarea() ?>
            </div>
        </div>

        <div class="form-group">
            <?php if ($allowBtnSave || $model->items[0]->isChanges()): ?>
                <?= Html::submitButton('Сохранить', [
                    'class' => 'btn btn-light btn-outline-primary btn-sm me-2'
                ]) ?>
            <?php elseif (!$model->date_close || $model->items[0]->isChanges() && $model->items[0]->quantity > 0): ?>
                <?= Html::a('Провести', '/increase/apply', [
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
                <?= Html::a('Вернуть на остаток', '/increase/cancel', [
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
            <?= Html::a('К списку', '/increase', ['class' => 'btn btn-light btn-outline-secondary btn-sm']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
