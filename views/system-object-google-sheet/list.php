<?php

use yii\data\ActiveDataProvider;
use yii\grid\GridView;

/** @var ActiveDataProvider $dataProvider Данные */

$header = 'Связь объектов с Google';

$this->registerJs('let controllerName = "system-object-google-sheet";', \yii\web\View::POS_HEAD);
$this->registerJsFile('@web/js/contextmenu-list.js');
?>

<div class="page-content">
    <div class="page-top-panel">
        <div class="page-top-panel-header d-flex">
            <?= $header ?>
            <a href="/system-object-google-sheet/create"
               class="btn btn-light btn-outline-secondary btn-sm mt-1 ms-auto pe-3">
                <i class="fa fa-plus"></i>
                <span class="ms-2">Добавить</span>
            </a>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,

        'rowOptions' => function ($model, $key, $index, $grid) {
            return [
                'class' => 'contextMenuRow',
                'data-row-id' => $model->system_object_id .  '/' . $model->google_sheet_id,
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

            // Название объекта
            [
                'attribute' => 'system_object_id',
                'value' => 'systemObject.name',
                'enableSorting' => false,
                'headerOptions' => [
                    'style' => 'width: 280px;'
                ],
                'filterInputOptions' => [
                    'class' => 'form-control form-control-sm',
                ],
            ],

            // Таблица Google
            [
                'attribute' => 'google_sheet_id',
                'value' => 'googleSheet.name',
                'enableSorting' => false,
                'headerOptions' => [
                    'style' => 'width: 280px;'
                ],
                'filterInputOptions' => [
                    'class' => 'form-control form-control-sm',
                ],
            ],

            // Диапазон
            [
                'attribute' => 'google_sheet_range',
                'enableSorting' => false,
                'headerOptions' => [
                    'style' => 'width: 280px;'
                ],
                'contentOptions' => [
                    'style' => ' font-family: Courier New, sans-serif; padding-bottom: 2px;',
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
            ],
        ],
    ]); ?>
</div>
