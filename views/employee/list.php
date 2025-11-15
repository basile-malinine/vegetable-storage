<?php

use yii\data\ActiveDataProvider;
use yii\grid\GridView;

/** @var ActiveDataProvider $dataProvider Данные */

$header = 'Сотрудники';

$this->registerJs('let controllerName = "employee";', \yii\web\View::POS_HEAD);
$this->registerJsFile('@web/js/contextmenu-list.js');
?>

<div class="page-content">
    <div class="page-top-panel">
        <div class="page-top-panel-header d-flex">
            <?= $header ?>
<!--            --><?php //if (Yii::$app->user->can('employee.create')): ?>
            <a href="/employee/create" class="btn btn-light btn-outline-secondary btn-sm mt-1 ms-auto pe-3">
                <i class="fa fa-plus"></i>
                <span class="ms-2">Добавить</span>
            </a>
<!--            --><?php //endif ?>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,

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

            // ФИО
            [
                'format' => 'raw',
                'attribute' => 'full_name',
                'enableSorting' => false,
                'headerOptions' => [
                    'style' => 'width: 280px;'
                ],
            ],

            // Телефон
            [
                'format' => 'raw',
                'attribute' => 'phone',
                'enableSorting' => false,
                'headerOptions' => [
                    'style' => 'width: 180px;'
                ],
            ],

            // Email
            [
                'format' => 'raw',
                'attribute' => 'email',
                'enableSorting' => false,
                'headerOptions' => [
                    'style' => 'width: 260px;'
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
