<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var yii\data\ActiveDataProvider $dataProviderItem */
/** @var Packing $model */

/** @var string $header */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\widgets\Pjax;
use kartik\date\DatePicker;
use kartik\select2\Select2;

use app\models\Assortment\Assortment;
use app\models\Documents\Packing\Packing;
use app\models\LegalSubject\LegalSubject;
use app\models\Stock\Stock;

$actionId = Yii::$app->controller->action->id;
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

        <?php Pjax::begin(['id' => 'pacing-qnt-weight-info']); ?>
        <div class="row form-row">
            <!-- Дата объединения -->
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

            <!-- Номенклатура -->
            <div class="form-col col-3">
                <?= $form->field($model, 'assortment_id')->widget(Select2::class, [
                    // Временно, пока не разработан алгоритм для штучной Номенклатуры...
                    'data' => Assortment::getList(['weight' => 1.0]),
                    'options' => [
                        'id' => 'assortment',
                        'placeholder' => 'Не назначена',
                        'disabled' => $actionId == 'edit',
                    ],
                ]); ?>
            </div>

            <!-- Единица / Вес-->
            <div class="form-col col-1">
                <?= $form->field($model, 'assortmentInfo')->textInput([
                    'id' => 'assortment-info',
                    'class' => 'form-control form-control-sm text-center',
                    'disabled' => true,
                ]) ?>
            </div>

            <!-- Количество -->
            <div class="form-col col-1">
                <?= $form->field($model, 'quantity')->textInput([
                    'id' => 'quantity',
                    'class' => 'form-control form-control-sm text-end',
                    'disabled' => true,
                ]) ?>
            </div>

            <!-- Количество паллет -->
            <div class="form-col col-1">
                <?= $form->field($model, 'quantity_pallet')->textInput([
                    'id' => 'quantity',
                    'class' => 'form-control form-control-sm text-end',
                    'disabled' => true,
                ]) ?>
            </div>

            <!-- Количество тары -->
            <div class="form-col col-1">
                <?= $form->field($model, 'quantity_paks')->textInput([
                    'id' => 'quantity',
                    'class' => 'form-control form-control-sm text-end',
                    'disabled' => true,
                ]) ?>
            </div>

            <!-- Вес -->
            <div class="form-col col-1">
                <?= $form->field($model, 'weight')->textInput([
                    'id' => 'weight',
                    'class' => 'form-control form-control-sm text-end',
                    'disabled' => true,
                ]) ?>
            </div>
        </div>
        <?php Pjax::end(); ?>

        <div class="row form-row">
            <!-- Предприятие -->
            <div class="form-col col-4">
                <?= $form->field($model, 'company_own_id')->widget(Select2::class, [
                    'data' => LegalSubject::getList('is_own = true'),
                    'options' => [
                        'id' => 'company-own-id',
                        'placeholder' => 'Не назначено',
                        'disabled' => $actionId == 'edit',
                    ],
                ]); ?>
            </div>

            <!-- Склад -->
            <div class="form-col col-2">
                <?= $form->field($model, 'stock_id')->widget(Select2::class, [
                    'data' => Stock::getList(),
                    'options' => [
                        'id' => 'stock-sender-id',
                        'placeholder' => 'Не назначено',
                        'disabled' => $actionId == 'edit',
                    ],
                ]); ?>
            </div>

            <!-- Кнопка добавления позиции -->
            <div class="form-col col-4 d-flex justify-content-end align-items-end mb-3">
                <?= Html::button('<i class="fa fa-plus"></i><span class="ms-2">Добавить приёмку</span>',
                    [
                        'id' => 'btn-add',
                        'class' => 'btn btn-light btn-outline-secondary btn-sm mt-1 pe-3',
                        'style' => 'height: 31px',
                        'hidden' => $actionId === 'create',
                    ]);
                ?>
            </div>
        </div>

        <!-- GridView (Состав) -->
        <div class="row form-row">
            <div class="form-col col-10">
                <?= isset($dataProviderItem) ? $this->render('_item_grid', compact(['model', 'dataProviderItem'])) : '' ?>
            </div>
            <div style="margin-top: -15px">
                <?= $form->field($model, 'errorItems')->input('text', [
                    'style' => 'display: none;',
                ])->label(false) ?>
            </div>
        </div>

        <!-- Комментарий -->
        <div <?= $actionId === 'create' ? 'class="row form-last-row mt-2"' : 'class="row form-last-row"' ?>>
            <div class="form-col col-10">
                <?= $form->field($model, 'comment')->textarea() ?>
            </div>
        </div>

        <div class="form-group">
            <?php if ($model->isChanges() || !$model->items) : ?>
                <?= Html::submitButton('Сохранить', [
                    'class' => 'btn btn-light btn-outline-primary btn-sm me-2'
                ]) ?>
            <?php elseif (!$model->date_close): ?>
                <?= Html::a('Закрыть', '/packing/close', [
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
                <?= Html::a('Открыть', '/packing/open', [
                    'id' => 'btn-change-open',
                    'class' => 'btn btn-light btn-outline-secondary btn-sm',
                    'data' => [
                        'method' => 'post',
                        'params' => [
                            'id' => $model->id,
                        ],
                    ],
                ]) ?>
            <?php endif; ?>
            <?= Html::a('К списку', '/packing', ['class' => 'btn btn-light btn-outline-secondary btn-sm']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
