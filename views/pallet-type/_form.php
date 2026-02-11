<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var PalletType $model */

/** @var string $header */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use app\models\PalletType\PalletType;

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
            <!-- Наименование -->
            <div class="form-col col-3">
                <?= $form->field($model, 'name')->textInput(
                    [
                        'maxlength' => true,
                    ]
                ) ?>
            </div>

            <!-- Отгружено -->
            <div class="form-col col-1">
                <?= $form->field($model, 'priority')->textInput([
                    'class' => 'form-control form-control-sm text-end',
                ]) ?>
            </div>
        </div>

        <!-- Комментарий -->
        <div class="row form-last-row">
            <div class="form-col col-4">
                <?= $form->field($model, 'comment')->textarea() ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-light btn-outline-primary btn-sm me-2']) ?>
            <?= Html::a('Отмена', '/pallet-type/index', ['class' => 'btn btn-light btn-outline-secondary btn-sm']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
