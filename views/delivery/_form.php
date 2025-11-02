<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var yii\data\ActiveDataProvider $dataProviderItem */
/** @var Delivery $model */

/** @var string $header */

use app\models\Documents\Delivery\Delivery;
use app\models\LegalSubject\LegalSubject;
use app\models\Manager\Manager;
use app\models\Stock\Stock;
use kartik\datetime\DateTimePicker;
use kartik\select2\Select2;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\web\View;

$actionID = Yii::$app->controller->action->id;
if ($actionID !== 'create' && !$model->date_close) {
    $deliveryId = $model->id;
    $this->registerJs('let deliveryId = ' . $deliveryId . ';', View::POS_HEAD);
    $this->registerJsFile('@web/js/delivery.js');
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
            <!-- Предприятие -->
            <div class="form-col col-6">
                <?= $form->field($model, 'own_id')->widget(Select2::class, [
                    'data' => LegalSubject::getListOwn(),
                ]); ?>
            </div>

            <!-- Склад -->
            <div class="form-col col-2">
                <?= $form->field($model, 'stock_id')->widget(Select2::class, [
                    'data' => Stock::getList(),
                ]); ?>
            </div>

            <!-- Менеджер -->
            <div class="form-col col-2">
                <?= $form->field($model, 'manager_id')->widget(Select2::class, [
                    'data' => Manager::getList(),
                ]); ?>
            </div>
        </div>

        <div class="row form-row">
            <!-- Поставщик -->
            <div class="form-col col-6">
                <?= $form->field($model, 'supplier_id')->widget(Select2::class, [
                    'data' => LegalSubject::getListSupplier(),
                ]) ?>
            </div>

            <!-- Дата ожидания -->
            <div class="form-col col-2">
                <?= $form->field($model, 'date_wait')->widget(DateTimePicker::class, [
                    'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                    'name' => 'closed',
                    'readonly' => true,
                    'disabled' => $model->date_close,
                    'size' => 'sm',

                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'dd.mm.yyyy hh:ii',
                        'todayHighlight' => true,
                    ],
                ]) ?>
            </div>

            <!-- Кнопка добавления позиции -->
            <div class="form-col col-2 d-flex align-items-center pt-2">
                <?= Html::button('<i class="fa fa-plus"></i><span class="ms-2">Добавить позицию</span>',
                    [
                        'id' => 'btnAdd',
                        'class' => 'btn btn-light btn-outline-secondary btn-sm mt-1 ms-auto pe-3',
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
        <div class="row form-last-row">
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
