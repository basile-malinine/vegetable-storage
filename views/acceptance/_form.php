<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var yii\data\ActiveDataProvider $dataProviderItem */
/** @var Acceptance $model */

/** @var string $header */

/** @var string $docLabel */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use kartik\date\DatePicker;
use kartik\select2\Select2;

use app\models\Documents\Acceptance\Acceptance;

$acceptanceTypes = Acceptance::TYPE_LIST;
unset(
    $acceptanceTypes[Acceptance::TYPE_INCREASE],
    $acceptanceTypes[Acceptance::TYPE_SORTING],
    $acceptanceTypes[Acceptance::TYPE_MERGING],
    $acceptanceTypes[Acceptance::TYPE_PACKING]
);

$docLabel = $docLabel ?? null;
$actionID = Yii::$app->controller->action->id;
?>

<div class="page-top-panel">
    <div class="page-top-panel-header d-inline">
        <?= $header ?>
        <div class="fs-6"<?= !$docLabel ? 'hidden' : '' ?>><?= ' ' . $docLabel ?></div>
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
            <!-- Дата приёмки -->
            <div class="form-col col-2">
                <?= $form->field($model, 'date')->widget(DatePicker::class, [
                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
                    'name' => 'closed',
                    'readonly' => true,
                    'disabled' => $model->date_close,
                    'size' => 'sm',

                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'dd.mm.yyyy',
                        'todayHighlight' => true,
                    ],
                ]) ?>
            </div>

            <!-- Тип -->
            <div class="form-col col-2" <?= $actionID == 'edit' ? 'hidden' : '' ?>>
                <?= $form->field($model, 'type_id')->widget(Select2::class, [
                    'data' => $acceptanceTypes,
                    'options' => [
                        'placeholder' => 'Не назначен',
                        'id' => 'acceptance-type',
                    ],
                ]); ?>
            </div>

            <!-- По документу -->
            <div class="form-col col-6" <?= $actionID == 'edit' ? 'hidden' : '' ?>>
                <?= $form->field($model, 'parent_doc_id')->widget(Select2::class, [
                    'data' => [],
                    'options' => [
                        'id' => 'parent-doc',
                        'placeholder' => 'Не назначено',
                    ],
                ]); ?>
            </div>
        </div>

        <div class="row form-row" hidden>
            <!-- Поставка -->
            <div class="form-col col-4">
                <?= $form->field($model, 'delivery_id')->input('text', [
                    'id' => 'delivery-id',
                ]); ?>
            </div>

            <!-- Предприятие -->
            <div class="form-col col-4">
                <?= $form->field($model, 'company_own_id')->input('text', [
                    'id' => 'company-own-id',
                ]); ?>
            </div>

            <!-- Склад -->
            <div class="form-col col-2" id="stock-div">
                <?= $form->field($model, 'stock_id')->input('text', [
                    'id' => 'stock-id',
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
            <!-- 'Сохранить', 'Закрыть' или 'Открыть'-->
            <?php if ($actionID == 'create'): ?>
                <?= Html::submitButton('Сохранить', [
                    'class' => 'btn btn-light btn-outline-primary btn-sm me-2'
                ]) ?>
            <?php elseif (!$model->date_close || $model->items[0]->isChanges() && $model->items[0]->quantity > 0): ?>
                <?= Html::a('Провести', '/acceptance/apply', [
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
                <?= Html::a('Снять с остатка', '/acceptance/remove-remainder', [
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
            <?= Html::a('К списку', '/acceptance', ['class' => 'btn btn-light btn-outline-secondary btn-sm']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
