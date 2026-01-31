<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var ShipmentAcceptance $model */

/** @var string $header */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use yii\web\View;
use kartik\select2\Select2;

use app\models\Assortment\Assortment;
use app\models\Documents\Remainder\Remainder;
use app\models\Documents\Shipment\Shipment;
use app\models\Documents\Shipment\ShipmentAcceptance;
use app\models\PalletType\PalletType;

$actionId = Yii::$app->controller->action->id;

$acceptanceList = [];
// Массив ID Приёмок уже добавленных в Отгрузку, которые при добавлении нужно исключить
$ids = ArrayHelper::getColumn($model->shipment->acceptances, 'id');
// При редактировании добавленной Приёмки удаляем из списка исключённых Приёмок текущую
if ($actionId === 'edit') {
    $ids = array_diff($ids, [$model->acceptance_id]);
}
$company_own_id = $model->shipment->company_own_id;
$stock_id = $model->shipment->stock_id;
$assortment_id = $model->shipment->parentDoc->items[0]->assortment_id;

switch ($model->shipment->type_id) {
    case Shipment::TYPE_ORDER:
        // По Заказам выбираем любую Приёмку из подгруппы позиции в Заказе с учётом весовая / не весовая
        $groupId = Assortment::findOne($assortment_id)->assortment_group_id;
        $isWeight = Assortment::findOne($assortment_id)->unit->is_weight;
        $assortmentIds = Assortment::find()->select('assortment.id')
            ->joinWith('unit')
            ->where(['assortment_group_id' => $groupId])
            ->andWhere(['unit.is_weight' => $isWeight])
            ->column();
        $acceptanceList = Remainder::getListAcceptance(
            $company_own_id,
            $stock_id,
            $assortmentIds,
            $ids
        );
        break;
    default:
        $acceptanceList = Remainder::getListAcceptance(
            $company_own_id,
            $stock_id,
            $assortment_id,
            $ids
        );
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
            'id' => 'shipment-acceptance-form',
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
                                $("[data-attribute = quantity]").val(data["quantity"]);
                                $("[data-attribute = quantity]").removeClass("is-invalid");
                                $("#hidden-pallet-type-id").val(data["pallet_type_id"]);
                                $("#pallet-type-id").val(data["pallet_type_id"]).trigger("change");
                                if (+data["quantity_pallet"] > 0) {
                                    $("[data-attribute = quantity-pallet]").removeAttr("disabled");
                                    $("[data-attribute = quantity-pallet]").val(data["quantity_pallet"]);
                                    $("[data-attribute = quantity-pallet]").removeClass("is-invalid");
                                } else {
                                    $("[data-attribute = quantity-pallet]").val("").trigger("change");
                                    $("[data-attribute = quantity-pallet]").attr("disabled", true);
                                }
                                if (+data["quantity_paks"] > 0) {
                                    $("[data-attribute = quantity-paks]").removeAttr("disabled");
                                    $("[data-attribute = quantity-paks]").val(data["quantity_paks"]);
                                    $("[data-attribute = quantity-paks]").removeClass("is-invalid");
                                } else {
                                    $("[data-attribute = quantity-paks]").val("").trigger("change");
                                    $("[data-attribute = quantity-paks]").attr("disabled", true);
                                }
//                                $("#shipment-acceptance-form").find(".is-invalid").removeClass("is-invalid");
                            }
                        );',
                    ],
                ]) ?>
            </div>
        </div>

        <div class="row form-row">
            <!-- Количество -->
            <div class="form-col col-3">
                <?= $form->field($model, 'quantity')->textInput([
                    'data-attribute' => 'quantity',
                    'maxlength' => true,
                    'class' => 'form-control form-control-sm text-end',
                ]) ?>
            </div>

            <!-- Тип паллет -->
            <div class="form-col col-3">
                <div hidden>
                    <?= $form->field($model, 'pallet_type_id')->textInput(['id' => 'hidden-pallet-type-id']) ?>
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
                    'data-attribute' => 'quantity-pallet',
                    'maxlength' => true,
                    'class' => 'form-control form-control-sm text-end',
                ]) ?>
            </div>

            <!-- Количество тары -->
            <div class="form-col col-3">
                <?= $form->field($model, 'quantity_paks')->textInput([
                    'data-attribute' => 'quantity-paks',
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
