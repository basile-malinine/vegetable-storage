<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var yii\data\ActiveDataProvider $dataProviderItem */
/** @var Delivery $model */

/** @var string $header */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\web\View;
use kartik\date\DatePicker;
use kartik\select2\Select2;

use app\models\Currency\Currency;
use app\models\Documents\Delivery\Delivery;
use app\models\LegalSubject\LegalSubject;
use app\models\Manager\Manager;
use app\models\PaymentMethod\PaymentMethod;
use app\models\Stock\Stock;

$actionID = Yii::$app->controller->action->id;

$this->registerCssFile('@web/css/brick-list.css');
$this->registerJs('let actionName = "' . Yii::$app->controller->action->id . '";', View::POS_HEAD);
$this->registerJsFile('@web/js/delivery.js');
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
            <!-- Тип -->
            <div class="form-col col-2">
                <?= $form->field($model, 'type_id')->widget(Select2::class, [
                    'data' => Delivery::TYPE_LIST,
                    'options' => [
                        'id' => 'delivery-type',
                        'disabled' => (bool)$model->orders,
                    ],
                ]); ?>
            </div>

            <!-- Поставщик -->
            <div class="form-col col-4">
                <?= $form->field($model, 'supplier_id')->widget(Select2::class, [
                    'data' => LegalSubject::getListSupplier(),
                    'options' => [
                        'placeholder' => 'Не назначен',
                    ],
                ]) ?>
            </div>

            <!-- Дата отгрузки -->
            <div class="form-col col-2">
                <?= $form->field($model, 'shipment_date')->widget(DatePicker::class, [
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

            <!-- Дата выгрузки -->
            <div class="form-col col-2">
                <?= $form->field($model, 'unloading_date')->widget(DatePicker::class, [
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
        </div>

        <div class="row form-row">
            <!-- Предприятие -->
            <div class="form-col col-4">
                <?= $form->field($model, 'company_own_id')->widget(Select2::class, [
                    'data' => LegalSubject::getList('is_supplier OR is_own'),
                    'options' => [
                        'placeholder' => 'Не назначено',
                        'disabled' => (bool)$model->orders,
                    ],
                ]); ?>
            </div>

            <!-- Склад -->
            <div class="form-col col-2" id="stock-div">
                <?= $form->field($model, 'stock_id')->widget(Select2::class, [
                    'data' => Stock::getList(),
                    'options' => [
                        'placeholder' => 'Не назначен',
                    ],
                ]); ?>
            </div>
            <!-- Исполнитель -->
            <div class="form-col col-2" id="executor-div">
                <?= $form->field($model, 'executor_id')->widget(Select2::class, [
                    'data' => Manager::getList('is_purchasing_mng'),
                    'options' => [
                        'id' => 'executorSelect',
                        'placeholder' => 'Не назначен',
                        'disabled' => (bool)$model->orders,
                    ],
                ]); ?>
            </div>

            <!-- Менеджер по закупкам -->
            <div class="form-col col-2">
                <?= $form->field($model, 'purchasing_mng_id')->widget(Select2::class, [
                    'data' => Manager::getList('is_purchasing_mng'),
                    'options' => [
                        'placeholder' => 'Не назначен',
                    ],
                ]); ?>
            </div>

            <!-- Агент по закупкам -->
            <div class="form-col col-2">
                <?= $form->field($model, 'purchasing_agent_id')->widget(Select2::class, [
                    'data' => Manager::getList('is_purchasing_agent'),
                    'options' => [
                        'placeholder' => 'Не назначен',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]); ?>
            </div>
        </div>

        <div class="row form-row">
            <!-- Менеджер по реализации -->
            <div class="form-col col-2">
                <?= $form->field($model, 'sales_mng_id')->widget(Select2::class, [
                    'data' => Manager::getList('is_sales_mng'),
                    'options' => [
                        'placeholder' => 'Не назначен',
                    ],
                ]); ?>
            </div>

            <!-- Отдел сопровождения -->
            <div class="form-col col-2">
                <?= $form->field($model, 'support_mng_id')->widget(Select2::class, [
                    'data' => Manager::getList('is_support'),
                    'options' => [
                        'placeholder' => 'Не назначен',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]); ?>
            </div>

            <!-- Валюта -->
            <div class="form-col col-2">
                <?= $form->field($model, 'currency_id')->widget(Select2::class, [
                    'data' => Currency::getList(),
                    'options' => [
                        'placeholder' => 'Не назначен',
                    ],
                ]); ?>
            </div>

            <!-- Способ оплаты -->
            <div class="form-col col-2">
                <?= $form->field($model, 'payment_method_id')->widget(Select2::class, [
                    'data' => PaymentMethod::getList(),
                    'options' => [
                        'placeholder' => 'Не назначен',
                    ],
                ]); ?>
            </div>

            <!-- Ставит транспорт -->
            <div class="form-col col-2">
                <?= $form->field($model, 'transport_affiliation_id')->widget(Select2::class, [
                    'data' => Delivery::TRANSPORT_AFFILIATION_LIST,
                    'options' => [
                        'placeholder' => 'Не назначен',
                    ],
                ]); ?>
            </div>
        </div>

        <div class="row form-row mb2">
            <!-- Привязанные заказы -->
            <div class="form-col col-8">
                <div id="order-list-div">
                    <?php
                    $items = [];
                    foreach ($model->orders as $order) {
                        $items[] = $order->label;
                    }
                    sort($items);
                    $values = '';
                    foreach ($items as $item) {
                        $values .= '<div class="set-item">' . $item . '</div>';
                    }
                    if (empty($values)) {
                        $values = '<div class="set-item-none">Нет</div>';
                    }
                    ?>
                    <div class="mb-3">
                        <label class="col-form-label pt-0">Привязанные Заказы</label>
                        <div class="set-container d-flex flex-row">
                            <div class="d-flex flex-column flex-wrap">
                                <?php echo $values; ?>
                            </div>
                            <?= Html::button('<i class="fa fa-ellipsis-h""></i>',
                                [
                                    'id' => 'btn-add-orders',
                                    'class' => 'btn-item-edit',
                                ]);
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Кнопка добавления позиции -->
            <div class="form-col col-2 d-flex justify-content-end align-items-end pb-3">
                <?= Html::button('<i class="fa fa-plus"></i><span class="ms-2">Добавить позицию</span>',
                    [
                        'id' => 'btnAdd',
                        'class' => 'btn btn-light btn-outline-secondary btn-sm mt-1 pe-3',
                        'style' => 'height: 31px',
                        'hidden' => $actionID === 'create' || $model->date_close,
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
            <?= Html::a('К списку', '/delivery/index', ['class' => 'btn btn-light btn-outline-secondary btn-sm']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
