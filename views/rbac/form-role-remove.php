<?php

/** @var Role $model */
/** @var string $header */

/** @var string $message */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use app\models\Rbac\Role;

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
            ],
            'enableAjaxValidation' => true,
        ]); ?>

        <?= $form->field($model, 'name')->textInput(['hidden' => true])->label(false) ?>
        <?= $form->field($model, 'description')->textInput(['hidden' => true])->label(false) ?>

        <div class="row form-last-row">
            <div class="text-center mb-3">
                <h6><?= $message ?></h6>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Продолжить', ['class' => 'btn btn-light btn-outline-primary btn-sm me-2']) ?>
            <?= Html::a('Отмена', '/rbac/index', ['class' => 'btn btn-light btn-outline-secondary btn-sm']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
