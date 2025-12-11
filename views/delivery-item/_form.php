<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var DeliveryItem $model */

/** @var string $header */

use app\models\Assortment\Assortment;
use app\models\Documents\Delivery\DeliveryItem;
use kartik\select2\Select2;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;

$actionId = Yii::$app->controller->action->id;
$assortmentList = [];
$ids = ArrayHelper::getColumn($model->delivery->items, 'assortment_id');

if ($actionId == 'add') {
    $assortmentList = Assortment::getListExceptIds($ids);
} elseif ($actionId == 'edit') {
    $assortmentList = Assortment::getListExceptIds($ids);
    $assortmentList = ArrayHelper::merge($assortmentList, [$model->assortment_id => $model->assortment->name]);
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
            <!-- Позиция -->
            <div class="form-col col-6">
                <?= $form->field($model, 'assortment_id')->widget(Select2::class, [
                    'data' => $assortmentList,
                    'options' => [
                        'placeholder' => 'Не назначена',
                        'onchange' => '
                            const $unit = $("#delivery-item-unit");
                            const $weight = $("#delivery-item-weight");
                            $.post(
                                "/assortment/get-unit-weight", 
                                {
                                    id: $(this).val()
                                }, 
                                (data) => {
                                    console.log(data);
                                    $unit.val(data.unit);
                                    $weight.val(data.weight);
                                }
                            );
                        ',
                    ],
                    'pluginOptions' => [
                        'matcher' => new JsExpression('matchStart'), // ф-ция из select2-helper.js
                        'dropdownParent' => '#modal', // необходимо указать id контекста
                    ],
                ]) ?>
            </div>

            <!-- Единица измерения -->
            <div class="form-col col-3">
                <?= Html::label('Ед. изм.', 'delivery-item-unit', ['class' => 'col-form-label pt-0']) ?>
                <?= Html::input('text', 'delivery-item-unit',
                    Yii::$app->controller->action->id !== 'add'
                        ? $model->assortment->unit->name
                        : '',
                    [
                        'id' => 'delivery-item-unit',
                        'class' => 'form-control form-control-sm',
                        'disabled' => true,
                    ]
                ) ?>
            </div>

            <!-- Вес единицы -->
            <div class="form-col col-3">
                <?= Html::label('Вес единицы', 'delivery-item-weight', ['class' => 'col-form-label pt-0']) ?>
                <?= Html::input('text', 'delivery-item-weight',
                    Yii::$app->controller->action->id !== 'add'
                        ? $model->assortment->weight
                        : '',
                    [
                        'id' => 'delivery-item-weight',
                        'class' => 'form-control form-control-sm text-end',
                        'disabled' => true,
                    ]
                ) ?>
            </div>
        </div>

        <div class="row form-last-row">
            <!-- Количество -->
            <div class="form-col col-4">
                <?= $form->field($model, 'quantity')->textInput([
                    'maxlength' => true,
                    'class' => 'form-control form-control-sm text-end',
                ]) ?>
            </div>

            <!-- Цена -->
            <div class="form-col col-4">
                <?= $form->field($model, 'price')->textInput([
                    'maxlength' => true,
                    'class' => 'form-control form-control-sm text-end',
                ]) ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-light btn-outline-primary btn-sm me-2']) ?>
            <?= Html::a('Закрыть', '/delivery/edit/' . $model->delivery_id, ['class' => 'btn btn-light btn-outline-secondary btn-sm']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
