<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var yii\data\ActiveDataProvider $dataProviderItem */
/** @var Moving $model */

/** @var string $header */

use app\models\Documents\Acceptance\Acceptance;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use kartik\date\DatePicker;
use kartik\select2\Select2;

use app\models\Documents\Moving\Moving;
use app\models\LegalSubject\LegalSubject;
use app\models\Stock\Stock;

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
            <!-- Дата перемещения -->
            <div class="form-col col-2">
                <?= $form->field($model, 'moving_date')->widget(DatePicker::class, [
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
            <div class="form-col col-6">
                <?= $form->field($model, 'acceptance_id')->widget(Select2::class, [
                    'data' => Acceptance::getListForMoving(),
                    'options' => [
                        'id' => 'acceptance-id',
                        'placeholder' => 'Не назначена',
                        'disabled' => $actionID === 'edit',
                    ],
                ]); ?>
            </div>
        </div>

        <div class="row form-row">
            <!-- Отправитель -->
            <div class="form-col col-4">
                <div hidden>
                    <?= $form->field($model, 'company_sender_id')->textInput([
                        'id' => 'hidden-company-sender-id',
                    ]) ?>
                </div>
                <?= $form->field($model, 'company_sender_id')->widget(Select2::class, [
                    'data' => LegalSubject::getList('is_own = true'),
                    'options' => [
                        'id' => 'company-sender-id',
                        'placeholder' => 'Не назначено',
                        'disabled' => true,
                    ],
                ]); ?>
            </div>

            <!-- Склад отправитель -->
            <div hidden>
                <?= $form->field($model, 'stock_sender_id')->textInput([
                    'id' => 'hidden-stock-sender-id',
                ]) ?>
            </div>
            <div class="form-col col-2">
                <?= $form->field($model, 'stock_sender_id')->widget(Select2::class, [
                    'data' => Stock::getList(),
                    'options' => [
                        'id' => 'stock-sender-id',
                        'placeholder' => 'Не назначено',
                        'disabled' => true,
                    ],
                ]); ?>
            </div>
        </div>

        <div class="row form-row">
            <!-- Получатель -->
            <div class="form-col col-4">
                <div hidden>
                    <?= $form->field($model, 'company_recipient_id')->textInput([
                        'id' => 'hidden-company-recipient-id',
                    ]) ?>
                </div>
                <?= $form->field($model, 'company_recipient_id')->widget(Select2::class, [
                    'data' => LegalSubject::getList('is_own = true'),
                    'options' => [
                        'id' => 'company-recipient-id',
                        'placeholder' => 'Не назначено',
                        'disabled' => true,
                    ],
                ]); ?>
            </div>

            <!-- Склад получатель -->
            <div class="form-col col-2">
                <?= $form->field($model, 'stock_recipient_id')->widget(Select2::class, [
                    'data' => [],
                    'options' => [
                        'id' => 'stock-recipient-id',
                        'placeholder' => 'Не назначено',
                        'disabled' => (bool)$model->date_close,
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
            <?= Html::a('К списку', '/moving', ['class' => 'btn btn-light btn-outline-secondary btn-sm']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
