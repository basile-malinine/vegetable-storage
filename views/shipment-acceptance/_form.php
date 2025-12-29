<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var ShipmentAcceptance $model */

/** @var string $header */

use app\models\PalletType\PalletType;
use app\models\Remainder\Remainder;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;

use kartik\select2\Select2;

use app\models\Documents\Shipment\ShipmentAcceptance;

$actionId = Yii::$app->controller->action->id;

$acceptanceList = [];
$ids = ArrayHelper::getColumn($model->shipment->acceptances, 'id');
$company_own_id = $model->shipment->company_own_id;
$stock_id = $model->shipment->stock_id;
$assortment_id = $model->shipment->parentDoc->items[0]->assortment_id;

if ($actionId == 'add') {
    $acceptanceList = Remainder::getListAcceptance(
        $company_own_id,
        $stock_id,
        $assortment_id,
        $ids
    );
} elseif ($actionId == 'edit') {
    $ids = array_diff($ids, [$model->acceptance_id]);
    $acceptanceList = Remainder::getListAcceptance(
        $company_own_id,
        $stock_id,
        $assortment_id,
        $ids
    );
}

$this->registerJsFile('@web/js/select2-helper.js', ['position' => \yii\web\View::POS_HEAD]);
?>

<div class="page-top-panel">
    <div class="page-top-panel-header d-inline">
        <?= $header ?>
    </div>
</div>

<div class="page-content">
    <div class="page-content-form">
        <?php $form = ActiveForm::begin([
            'id' => 'order-item-form',
            'fieldConfig' => [
                'template' => "{label}\n{input}\n{error}",
                'labelOptions' => ['class' => 'col-form-label pt-0'],
                'inputOptions' => ['class' => 'form-control form-control-sm'],
                'errorOptions' => ['class' => 'invalid-feedback'],
            ],
            'enableAjaxValidation' => true,
        ]); ?>

        <div class="row form-row">
            <!-- Приёмка -->
            <div class="form-col col-12">
                <?= $form->field($model, 'acceptance_id')->widget(Select2::class, [
                    'id' => 'acceptance-id',
                    'data' => $acceptanceList,
                    'options' => [
                        'placeholder' => 'Не назначена',
                        'onchange' => '$.post(
                            "/shipment-acceptance/change-acceptance",
                            { acceptance_id: $(this).val() },
                            (data) => {
                                $("#quantity").val(data["quantity"]);
                                if (data["pallet_type_id"]) {
                                    $("#hidden-pallet-type-id").val(data["pallet_type_id"]);
                                    $("#pallet-type-id").val(data["pallet_type_id"]).trigger("change");
                                }
                                if (+data["quantity_pallet"] > 0) {
                                    $("#quantity-pallet").val(data["quantity_pallet"]);
                                } else {
                                    $("#quantity-pallet").attr("disabled", true);
                                }
                                if (+data["quantity_paks"] > 0) {
                                    $("#quantity-paks").val(data["quantity_paks"]);
                                } else {
                                    $("#quantity-paks").attr("disabled", true);
                                }
                            }
                        );',
                    ],
                    'pluginOptions' => [
                        'matcher' => new JsExpression('matchStart'), // ф-ция из select2-helper.js
                        'dropdownParent' => '#modal', // необходимо указать id контекста
                    ],
                ]) ?>
            </div>
        </div>

        <div class="row form-row">
            <!-- Количество -->
            <div class="form-col col-3">
                <?= $form->field($model, 'quantity')->textInput([
                    'id' => 'quantity',
                    'maxlength' => true,
                    'class' => 'form-control form-control-sm text-end',
                ]) ?>
            </div>

            <!-- Тип паллет -->
            <div class="form-col col-3">
                <div hidden>
                    <?=  $form->field($model, 'pallet_type_id')->textInput(['id' => 'hidden-pallet-type-id']) ?>
                </div>
                <?= $form->field($model, 'pallet_type_id')->widget(Select2::class, [
                    'data' => PalletType::getList(),
                    'options' => [
                        'id' => 'pallet-type-id',
                        'placeholder' => 'Не назначен',
                        'disabled' => true,
                    ],
                ]) ?>
            </div>

            <!-- Количество паллет -->
            <div class="form-col col-3">
                <?= $form->field($model, 'quantity_pallet')->textInput([
                    'id' => 'quantity-pallet',
                    'maxlength' => true,
                    'class' => 'form-control form-control-sm text-end',
                ]) ?>
            </div>

            <!-- Количество тары -->
            <div class="form-col col-3">
                <?= $form->field($model, 'quantity_paks')->textInput([
                    'id' => 'quantity-paks',
                    'maxlength' => true,
                    'class' => 'form-control form-control-sm text-end',
                ]) ?>
            </div>
        </div>

        <!-- Комментарий -->
        <div class="row form-last-row">
            <div class="form-col col">
                <?= $form->field($model, 'comment')->textarea() ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-light btn-outline-primary btn-sm me-2']) ?>
            <?= Html::a('Закрыть', '/shipment/edit/' . $model->shipment_id, ['class' => 'btn btn-light btn-outline-secondary btn-sm']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
