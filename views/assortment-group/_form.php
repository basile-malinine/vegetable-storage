<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

use app\models\Assortment\AssortmentGroup;

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var AssortmentGroup $model */
/** @var string $header */
/** @var string $parent_id */

$this->registerCssFile('@web/css/brick-list.css');

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
                'inputOptions' => ['class' => 'form-control form-control-sm m-0'],
                'errorOptions' => ['class' => 'invalid-feedback'],
                'enableClientValidation' => false,
            ],
        ]); ?>

        <div class="row <?= Yii::$app->requestedAction->id == 'create' || $parent_id ? 'form-last-row' : 'form-row' ?>">
            <!-- Группа -->
            <div class="col-4">
                <?= $form->field($model, 'name')->textInput([
                    'maxlength' => true,
                ])->label($parent_id ? 'Подгруппа' : 'Группа') ?>
            </div>
        </div>

        <div class="row form-last-row" <?= Yii::$app->requestedAction->id == 'create' || $parent_id ? 'hidden' : '' ?>>
            <div class="col-4">
                <!-- Подгруппы -->
                <!-- При создании не отображается -->
                <?php
                $items = [];
                foreach ($model->child as $child) {
                    $items[] = $child->name;
                }
                sort($items);
                $values = '';
                foreach ($items as $item) {
                    $values .= '<div class="set-item">' . $item . '</div>';
                }
                if (empty($values)) {
                    $values = '<div class="set-item-none">Нет</div>';
                }
                ?>
                <div class="mb-3">
                    <label class="col-form-label pt-0">Подгруппы</label>
                    <div class="set-container d-flex flex-row">
                        <div class="d-flex flex-row flex-wrap">
                            <?php echo $values; ?>
                        </div>
                        <a href="/assortment-group/index/<?= $model->id ?>" id="assortment-group"
                           class="btn-item-edit"><i class="fa fa-ellipsis-h"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-light btn-outline-primary btn-sm me-2']) ?>
            <?= Html::a('Отмена', '/assortment-group/index/' . $parent_id ?: '',
                ['class' => 'btn btn-light btn-outline-secondary btn-sm']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
