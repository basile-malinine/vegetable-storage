<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var SystemObjectGoogleSheet $model */

/** @var string $header */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\web\JsExpression;
use kartik\select2\Select2;

use app\models\GoogleSheet\GoogleSheet;
use app\models\SystemObject\SystemObject;
use app\models\SystemObjectGoogleSheet\SystemObjectGoogleSheet;

$this->registerJsFile('@web/js/select2-helper.js', ['position' => \yii\web\View::POS_HEAD]);
?>

<div class="page-top-panel">
    <div class="page-top-panel-header">
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

        <!-- Объект в системе -->
        <div class="row form-row">
            <div class="form-col col-4">
                <?= $form->field($model, 'system_object_id')->widget(Select2::class,
                    [
                        'data' => SystemObject::getList(),
                        'options' => [
                            'placeholder' => 'Не назначен',
                        ],
                        'pluginOptions' => [
                            'matcher' => new JsExpression('matchStart'),
                        ],
                    ]
                ) ?>
            </div>
        </div>

        <!-- Таблица в Google -->
        <div class="row form-row">
            <div class="form-col col-4">
                <?= $form->field($model, 'google_sheet_id')->widget(Select2::class,
                    [
                        'data' => GoogleSheet::getList(),
                        'options' => [
                            'placeholder' => 'Не назначена',
                        ],
                        'pluginOptions' => [
                            'matcher' => new JsExpression('matchStart'),
                        ],
                    ]
                ) ?>
            </div>
        </div>

        <!-- Диапазон -->
        <div class="row form-row">
            <div class="form-col col-4">
                <?= $form->field($model, 'google_sheet_range')->textInput() ?>
            </div>
        </div>

        <!-- Комментарий -->
        <div class="row form-last-row">
            <div class="form-col col-4">
                <?= $form->field($model, 'comment')->textarea() ?>
            </div>
        </div>

            <?= Html::submitButton('Сохранить', [
                'id' => 'submit-btn',
                'class' => 'btn btn-light btn-outline-primary btn-sm me-2',
            ]) ?>

            <?= Html::a('Отмена', '/system-object-google-sheet/index', ['class' => 'btn btn-light btn-outline-secondary btn-sm']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
