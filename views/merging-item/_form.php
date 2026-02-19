<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var MergingItem $model */

/** @var string $header */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

use app\models\Assortment\Assortment;
use app\models\Assortment\AssortmentGroup;
use app\models\Documents\Merging\MergingItem;
use app\models\Documents\Remainder\Remainder;
use app\models\PalletType\PalletType;

$actionId = Yii::$app->controller->action->id;

$acceptanceList = [];
// Массив ID Приёмок уже добавленных в Объединение, которые при добавлении нужно исключить
$ids = ArrayHelper::getColumn($model->merging->sourceAcceptances, 'id');
// При редактировании добавленной Приёмки удаляем из списка исключённых Приёмок текущую
if ($actionId === 'edit') {
    $ids = array_diff($ids, [$model->acceptance_id]);
}
$company_own_id = $model->merging->company_own_id;
$stock_id = $model->merging->stock_id;

$assortment = $model->merging->assortment;
$groupId = $assortment->assortment_group_id;
$isWeight = $assortment->unit->is_weight;
$weight = $assortment->weight;
$parentGroupId = $assortment->parent_id; // Основная группа Классификатора.
// Получаем Ids подгрупп для основной группы.
$assortmentGroupIds = AssortmentGroup::find()
    ->select('id')
    ->where(['parent_id' => $parentGroupId])
    ->column();
// Получаем Ids Номенклатуры из основной группы Классификатора.
$assortmentIds = Assortment::find()
    ->select('assortment.id')
    ->joinWith('unit')
    ->andWhere(['assortment_group_id' => $assortmentGroupIds])
    ->andWhere(['unit.is_weight' => $isWeight])
    ->andWhere(['assortment.weight' => $weight])
    ->column();
// Список Приёмок на остатке.
$acceptanceList = Remainder::getListAcceptance(
    $company_own_id,
    $stock_id,
    $assortmentIds,
    $ids,
    true
);

$this->registerJsFile('@web/js/merging-item.js');
?>

<div class="page-top-panel">
    <div class="page-top-panel-header d-inline">
        <?= $header ?>
    </div>
</div>

<div class="page-content">
    <div class="page-content-form">
        <?php $form = ActiveForm::begin([
            'id' => 'merging-item-form',
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
                    'data' => $acceptanceList,
                    'options' => [
                        'placeholder' => 'Не назначена',
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
                    <?= $form->field(
                        $actionId == 'add' ? $model : $model->acceptance->items[0],
                        'pallet_type_id')->textInput(['id' => 'hidden-pallet-type-id']
                    ) ?>
                </div>
                <?= $form->field(
                    $actionId == 'add' ? $model : $model->acceptance->items[0],
                    'pallet_type_id')->widget(Select2::class, [
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
                    // Если нет кол-ва паллет в исходной приёмке
                    'disabled' => $model->acceptance && !$model->acceptance->items[0]->quantity_pallet
                    ,
                ]) ?>
            </div>

            <!-- Количество тары -->
            <div class="form-col col-3">
                <?= $form->field($model, 'quantity_paks')->textInput([
                    'data-attribute' => 'quantity-paks',
                    'maxlength' => true,
                    'class' => 'form-control form-control-sm text-end',
                    // Если нет кол-ва тары в исходной приёмке
                    'disabled' => $model->acceptance && !$model->acceptance->items[0]->quantity_paks,
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
            <?= Html::a('Закрыть', '/merging/edit/' . $model->merging_id, [
                'class' => 'btn btn-light btn-outline-secondary btn-sm'
            ]) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
