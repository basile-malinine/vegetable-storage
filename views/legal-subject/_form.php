<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\web\View;
use kartik\select2\Select2;
use app\models\Country\Country;
use app\models\LegalSubject\LegalSubject;

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var LegalSubject $model */
/** @var string $header */

$this->registerCssFile('https://cdn.jsdelivr.net/npm/suggestions-jquery@21.12.0/dist/css/suggestions.min.css');
$this->registerJsFile('https://cdn.jsdelivr.net/npm/suggestions-jquery@21.12.0/dist/js/jquery.suggestions.min.js',
    ['position' => View::POS_END]);

$alfa2 = '';
$innName = 'ИНН';
$isLegal = true;
if (isset($model->is_legal) && isset($model->country)) {
    $innName = $model->is_legal ? $model->country->inn_legal_name : $model->country->inn_name;
    $isLegal = (bool)$model->is_legal;
    $alfa2 = $model->country->alfa2;
}

$this->registerJs('let countryCode = "' . $alfa2 . '";', View::POS_HEAD);
$this->registerJsFile('@web/js/dadata.legal-subject-form.js', ['position' => View::POS_END]);
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
            <!-- Страна -->
            <div class="form-col col-4">
                <?= $form->field($model, 'country_id')->widget(Select2::class, [
                    'data' => Country::getList(),
                    'options' => [
                        'onchange' => '
                            let isLegalField = $("#legalsubject-is_legal");
                            let labelInnField = $(".field-legalsubject-inn > .col-form-label")[0];
                            $.post(
                                "/country/get-inn-name", 
                                {id: $(this).val(), isLegal: isLegalField.val()}, 
                                (data) => {
                                    labelInnField.innerText = data;
                                }
                            );
                            $.post(
                                "/country/get-alfa2", 
                                {id: $(this).val()}, 
                                (data) => {
                                    countryCode = data;
                                    setInnSuggestions();
                                }
                            );
                        ',
                    ],
                ]); ?>
            </div>

            <!-- Поставщик -->
            <div class="form-col col-1" style="margin-top: 31px;">
                <?= $form->field($model, 'is_supplier')->checkbox() ?>
            </div>

            <!-- Покупатель -->
            <div class="form-col col-1" style="margin-top: 31px;">
                <?= $form->field($model, 'is_buyer')->checkbox() ?>
            </div>
        </div>

        <div class="row form-row">
            <!-- Тип -->
            <div class="form-col col-2">
                <?= $form->field($model, 'is_legal')->widget(Select2::class, [
                    'data' => [0 => 'ИП', 1 => 'Юридическое лицо'],
                    'hideSearch' => true,
                    'options' => [
                        'onchange' => '
                            let countryIdField = $("#legalsubject-country_id");
                            let labelInnField = $(".field-legalsubject-inn > .col-form-label")[0];
                            let labelNameField = $(".field-legalsubject-name > .col-form-label")[0];
                            let labelFullNameField = $(".field-legalsubject-full_name > .col-form-label")[0];
                            
                            $.post(
                                "/country/get-inn-name", 
                                {
                                    id: countryIdField.val(),
                                    isLegal: $(this).val()
                                }, 
                                (data) => {
                                    labelInnField.innerText = data;
                                }
                            );
                            
                            if ($(this).val() == 1) {
                                $(".form-row-legal").removeClass("d-none");
                                labelNameField.innerText = "Название организации";
                                labelFullNameField.innerText = "Полное название организации";
                            } else {
                                $(".form-row-legal").addClass("d-none");
                                labelNameField.innerText = "ФИО";
                                labelFullNameField.innerText = "ФИО (полностью)";
                            }',
                    ],
                ])->label('Тип'); ?>
            </div>

            <!-- ИНН -->
            <div class="form-col col-2">
                <?= $form->field($model, 'inn')->textInput(['maxlength' => true])->label($innName) ?>
            </div>

            <!-- Название или ФИО -->
            <div class="form-col col-2">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true])
                    ->label($model->is_legal ? 'Название организации' : 'ФИО') ?>
            </div>

            <!-- Полное название или ФИО -->
            <div class="form-col col-6">
                <?= $form->field($model, 'full_name')->textInput(['maxlength' => true])
                    ->label($model->is_legal ? 'Полное название организации' : 'ФИО (полностью)') ?>
            </div>
        </div>

        <div class="row form-row form-row-legal <?= !$isLegal ? 'd-none' : '' ?>">
            <!-- Директор -->
            <div class="form-col col-4">
                <?= $form->field($model, 'director')->textInput(['maxlength' => true]) ?>
            </div>

            <!-- Бухгалтер -->
            <div class="form-col col-4" hidden>
                <?= $form->field($model, 'accountant')->textInput(['maxlength' => true]) ?>
            </div>
        </div>

        <div class="row form-row">
            <!-- Адрес -->
            <div class="form-col col-12">
                <?= $form->field($model, 'address')->textinput(['maxlength' => true]) ?>
            </div>
        </div>

        <div class="row form-row">
            <!-- Контактная информация -->
            <div class="form-col col-12">
                <?= $form->field($model, 'contacts')->textarea() ?>
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
            <?= Html::a('Отмена', '/legal-subject/index', ['class' => 'btn btn-light btn-outline-secondary btn-sm']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
