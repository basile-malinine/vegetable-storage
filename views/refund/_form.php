<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var yii\data\ActiveDataProvider $dataProviderItem */
/** @var Refund $model */

/** @var string $header */
/** @var string $docLabel */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use kartik\date\DatePicker;
use kartik\select2\Select2;

use app\models\Documents\Order\Order;
use app\models\LegalSubject\LegalSubject;
use app\models\Manager\Manager;
use app\models\Stock\Stock;

use app\models\Documents\Refund\Refund;

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
            <!-- Дата возврата -->
            <div class="form-col col-2">
                <?= $form->field($model, 'date')->widget(DatePicker::class, [
                    'type' => DatePicker::TYPE_COMPONENT_APPEND,
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
            <div class="form-col col-2" <?=  $actionID  === 'edit' ? 'hidden' : '' ?>>
                <?= $form->field($model, 'type_id')->widget(Select2::class, [
                    'data' => Order::TYPE_LIST,
                    'options' => [
                        'placeholder' => 'Не назначен',
                        'id' => 'order-type',
                        'disabled' => $actionID === 'edit',
                    ],
                ]); ?>
            </div>
            <!-- Предприятие в заказе -->
            <div class="form-col col-4" <?=  $actionID  === 'edit' ? 'hidden' : '' ?>>
                <?= $form->field($model, 'order_company_own_id')->widget(Select2::class, [
                    'data' => LegalSubject::getList('is_own = true'),
                    'options' => [
                        'id' => 'order-company-own-id',
                        'placeholder' => 'Не назначено',
                        'disabled' => $actionID === 'edit',
                    ],
                ]); ?>
            </div>

            <!-- Склад в заказе -->
            <div class="form-col col-2" id="div-order-stock"
                <?= $model->type_id === Order::TYPE_EXECUTOR || $actionID  === 'edit' ? 'hidden' : '' ?>
            >
                <?= $form->field($model, 'order_stock_id')->widget(Select2::class, [
                    'data' => Stock::getList(),
                    'options' => [
                        'id' => 'order-stock-id',
                        'placeholder' => 'Не назначено',
                        'disabled' => $actionID === 'edit',
                    ],
                ]); ?>
            </div>

            <!-- Исполнитель в заказе -->
            <div class="form-col col-2" id="div-order-executor"
                <?= $model->type_id === Order::TYPE_STOCK || $actionID  === 'edit' ? 'hidden' : '' ?>
            >
                <?= $form->field($model, 'order_executor_id')->widget(Select2::class, [
                    'data' => Manager::getList('is_purchasing_mng'),
                    'options' => [
                        'id' => 'order-executor-id',
                        'placeholder' => 'Не назначено',
                        'disabled' => $actionID === 'edit',
                    ],
                ]); ?>
            </div>
        </div>

        <div class="row form-row" <?=  $actionID  === 'edit' ? 'hidden' : '' ?>>
            <!-- Заказ -->
            <div class="form-col col-8">
                <?= $form->field($model, 'order_id')->widget(Select2::class, [
                    'data' => [],
                    'options' => [
                        'id' => 'order-id',
                        'placeholder' => 'Не назначено',
                        'disabled' => $actionID === 'edit',
                    ],
                ]); ?>
            </div>
        </div>

        <div class="row form-row">
            <!-- Предприятие получатель -->
            <div class="form-col col-4">
                <div hidden>
                    <?= $form->field($model, 'company_own_id')->textInput([
                        'id' => 'hidden-company-own-id',
                    ]) ?>
                </div>
                <?= $form->field($model, 'company_own_id')->widget(Select2::class, [
                    'data' => LegalSubject::getList('is_own = true'),
                    'options' => [
                        'id' => 'company-own-id',
                        'placeholder' => 'Не назначено',
                        'disabled' => true,
                    ],
                ]); ?>
            </div>

            <!-- Склад получатель -->
            <div class="form-col col-2">
                <?= $form->field($model, 'stock_id')->widget(Select2::class, [
                    'data' => Stock::getList(),
                    'options' => [
                        'id' => 'stock-id',
                        'placeholder' => 'Не назначено',
                    ],
                ]); ?>
            </div>

            <!-- Статус возврата -->
            <div class="form-col col-2">
                <?= $form->field($model, 'status_id')->widget(Select2::class, [
                    'data' => Refund::STATUS_LIST,
                    'options' => [
                        'placeholder' => 'Не назначен',
                        'id' => 'refund-status',
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
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-light btn-outline-primary btn-sm me-2']) ?>
            <?= Html::a('К списку', '/refund', ['class' => 'btn btn-light btn-outline-secondary btn-sm']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
