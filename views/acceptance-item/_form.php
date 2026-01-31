<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var AcceptanceItem $model */

/** @var string $header */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

use app\models\Assortment\Assortment;
use app\models\Documents\Acceptance\AcceptanceItem;
use app\models\PalletType\PalletType;

$actionId = Yii::$app->controller->action->id;
$assortmentList = [];
$ids = ArrayHelper::getColumn($model->acceptance->items, 'assortment_id');

if ($actionId == 'add') {
    $assortmentList = Assortment::getListExceptIds($ids);
} elseif ($actionId == 'edit') {
    $assortmentList = Assortment::getListExceptIds($ids);
    $assortmentList = ArrayHelper::merge($assortmentList, [$model->assortment_id => $model->assortment->name]);
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
            'id' => 'delivery-item-form',
            'fieldConfig' => [
                'template' => "{label}\n{input}\n{error}",
                'labelOptions' => ['class' => 'col-form-label pt-0'],
                'inputOptions' => ['class' => 'form-control form-control-sm'],
                'errorOptions' => ['class' => 'invalid-feedback'],
            ],
            'enableAjaxValidation' => true,
        ]); ?>

        <div class="row form-row">
            <!-- Количество -->
            <div class="form-col col-3">
                <?= $form->field($model, 'quantity')->textInput([
                    'maxlength' => true,
                    'class' => 'form-control form-control-sm text-end',
                ]) ?>
            </div>

            <!-- Тип паллет -->
            <div class="form-col col-3">
                <?= $form->field($model, 'pallet_type_id')->widget(Select2::class, [
                    'data' => PalletType::getList(),
                    'options' => [
                        'placeholder' => 'Не назначен',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]) ?>
            </div>

            <!-- Кол-во паллет -->
            <div class="form-col col-3">
                <?= $form->field($model, 'quantity_pallet')->textInput([
                    'maxlength' => true,
                    'class' => 'form-control form-control-sm text-end',
                ]) ?>
            </div>

            <!-- Кол-во тары -->
            <div class="form-col col-3">
                <?= $form->field($model, 'quantity_paks')->textInput([
                    'maxlength' => true,
                    'class' => 'form-control form-control-sm text-end',
                ]) ?>
            </div>
        </div>

        <div class="row form-last-row">
            <!-- Комментарий -->
            <div class="form-col col-12">
                <?= $form->field($model, 'comment')->textarea() ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-light btn-outline-primary btn-sm me-2']) ?>
            <?= Html::a('Закрыть', '/acceptance/edit/' . $model->acceptance_id, ['class' => 'btn btn-light btn-outline-secondary btn-sm']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
