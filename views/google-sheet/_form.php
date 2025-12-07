<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var GoogleSheet $model */

/** @var string $header */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use app\models\GoogleSheet\GoogleSheet;
use app\models\UpdateGoogle;

$account = GoogleSheet::getAccount();
?>

<div class="page-top-panel">
    <div class="page-top-panel-header d-flex">
        <?= $header ?>
        <div class="ms-auto mt-2" style="font-size: medium;">
            Аккаунт приложения: <?= $account ?>
        </div>
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

        <!-- Sheet ID -->
        <div class="row form-row">
            <div class="form-col col-4">
                <?= $form->field($model, 'sheet_id')->textInput(
                    [
                        'id' => 'sheet_id',
                        'maxlength' => true,
                        'onchange' => '
                            $("#name").val("");
                            $("#submit-btn").attr("disabled", true);
                            $("#open-sheet").attr("hidden", true);
                        '
                    ]
                ) ?>
            </div>
            <div class="form-col col-2">
                <?= Html::img(Yii::getAlias('@web/images/sheet.webp'), [
                    'id' => 'open-sheet',
                    'style' => 'height: 70px; margin-top: 6px; cursor: pointer;',
                    'title' => 'Открыть',
                    'hidden' => true,
                    'onclick' => '
                        window.open("https://docs.google.com/spreadsheets/d/" + $("#sheet_id").val() + "/edit", "_blank");
                    ',
                ]) ?>
            </div>
        </div>

        <!-- Название -->
        <div class="row form-row">
            <div class="form-col col-4">
                <?= $form->field($model, 'name')->textInput(
                    [
                        'id' => 'name',
                        'readonly' => true,
                    ]
                ) ?>
            </div>
        </div>

        <!-- Комментарий -->
        <div class="row form-last-row">
            <div class="form-col col-4">
                <?= $form->field($model, 'comment')->textarea() ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::a('Тест', null, [
                'class' => 'btn btn-light btn-outline-secondary btn-sm me-2',
                'onclick' => '
                    $("#name").val("");
                    $("#submit-btn").attr("disabled", true);
                    $("#open-sheet").attr("hidden", true);
                    if ($("#sheet_id").val().trim() === "") {
                        alert("Sheet ID не должно быть пустым.");                    
                        $("#sheet_id").focus();
                        return;
                    }
                    $.post(
                        "/google-sheet/test", 
                        {spreadsheetId: $("#sheet_id").val()}, 
                        (data) => {
                            switch (data.errorCode) {
                                case 0:
                                    $("#name").val(data.title);
                                    $("#open-sheet").removeAttr("hidden");
                                    $("#submit-btn").removeAttr("disabled");
                                    break;
                                case 403:
                                    $("#open-sheet").removeAttr("hidden");
                                    alert("К этой таблице нет доступа у приложения.");
                                    break;
                                case 404:
                                    alert("Таблица с таким Sheet ID не найдена.");
                                    break;
                                default:
                                    alert("Неизвестная ошибка...");
                            }
                        }
                    );
                ',
            ]) ?>

            <?= Html::submitButton('Сохранить', [
                'id' => 'submit-btn',
                'class' => 'btn btn-light btn-outline-primary btn-sm me-2',
                'disabled' => true,
            ]) ?>

            <?= Html::a('Отмена', '/google-sheet/index', ['class' => 'btn btn-light btn-outline-secondary btn-sm']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
