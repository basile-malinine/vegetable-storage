<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var yii\data\ActiveDataProvider $dataProviderItem */
/** @var Refund $model */

/** @var string $header */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use kartik\date\DatePicker;
use kartik\select2\Select2;

use app\models\LegalSubject\LegalSubject;
use app\models\Stock\Stock;

use app\models\Documents\Refund\Refund;

$actionID = Yii::$app->controller->action->id;
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
            <!-- Дата возврата -->
            <div class="form-col col-2">
                <?= $form->field($model, 'refund_date')->widget(DatePicker::class, [
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
            <div class="form-col col-2">
                <?= $form->field($model, 'type_id')->widget(Select2::class, [
                    'data' => Refund::TYPE_LIST,
                    'options' => [
                        'placeholder' => 'Не назначен',
                        'id' => 'refund-type',
                        'disabled' => $actionID === 'edit',
                    ],
                ]); ?>
            </div>

            <!-- Заказ -->
            <div class="form-col col-6" <?= $actionID === 'edit' ? 'hidden' : '' ?>>
                <?= $form->field($model, 'order_id')->widget(Select2::class, [
                    'data' => [],
                    'options' => [
                        'id' => 'order-id',
                        'placeholder' => 'Не назначено',
                    ],
                ]); ?>
            </div>
        </div>

        <div class="row form-row">
            <!-- Предприятие -->
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

            <!-- Склад -->
            <div class="form-col col-2">
                <?= $form->field($model, 'stock_id')->widget(Select2::class, [
                    'data' => Stock::getList(),
                    'options' => [
                        'id' => 'stock-id',
                        'placeholder' => 'Не назначено',
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
