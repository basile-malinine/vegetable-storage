<?php

use yii\data\ActiveDataProvider;
use yii\grid\GridView;

/** @var ActiveDataProvider $dataProvider Данные */

$header = 'Менеджеры';

$this->registerJs('let controllerName = "manager";', \yii\web\View::POS_HEAD);
$this->registerJsFile('@web/js/contextmenu-list.js');
?>

<div class="page-content">
    <div class="page-top-panel">
        <div class="page-top-panel-header d-flex">
            <?= $header ?>
            <?php if (Yii::$app->user->can('manager.create')): ?>
            <a href="/manager/create" class="btn btn-light btn-outline-secondary btn-sm mt-1 ms-auto pe-3">
                <i class="fa fa-plus"></i>
                <span class="ms-2">Добавить</span>
            </a>
            <?php endif ?>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,

        'rowOptions' => function ($model, $key, $index, $grid) {
            return [
                'class' => 'contextMenuRow',
                'data-row-id' => $model->id,
            ];
        },

        'columns' => [
            // #
            [
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => [
                    'style' => 'width: 40px;'
                ],
                'contentOptions' => [
                    'style' => 'text-align: right;',
                ]
            ],

            // Название
            [
                'attribute' => 'name',
                'enableSorting' => false,
                'headerOptions' => [
                    'style' => 'width: 200px;'
                ],
                'filterInputOptions' => [
                    'class' => 'form-control form-control-sm',
                ],
            ],

            // Типы
            [
                'attribute' => 'types',
                'label' => 'Типы',
                'value' => function ($model) {
                    return implode(', ', $model->types);
                },
                'enableSorting' => false,
                'headerOptions' => [
                    'style' => 'width: 640px;'
                ],
                'filterInputOptions' => [
                    'class' => 'form-control form-control-sm',
                ],
            ],

            // Комментарий
            [
                'attribute' => 'comment',
                'enableSorting' => false,
                'filterInputOptions' => [
                    'class' => 'form-control form-control-sm',
                ],
            ]
        ],
    ]); ?>
</div>
