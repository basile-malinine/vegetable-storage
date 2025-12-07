<?php

use yii\bootstrap5\Html;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;

/** @var ActiveDataProvider $dataProvider Данные */

$header = 'Статус Местоположение';

$this->registerJs('let controllerName = "location-status";', \yii\web\View::POS_HEAD);
$this->registerJsFile('@web/js/contextmenu-list.js');
$this->registerJsFile('@web/js/google.js');
?>

<div class="page-content">
    <div class="page-top-panel">
        <div class="page-top-panel-header d-flex">
            <?= $header ?>
            <?= Html::a('<i class="fa fa-refresh"></i><span class="ms-2">Обновить Google</span>', null,
                [
                    'id' => 'google-update',
                    'class' => 'btn btn-light btn-outline-secondary btn-sm mt-1 ms-5 pe-3',
                ]
            ) ?>

            <a href="/location-status/create" class="btn btn-light btn-outline-secondary btn-sm mt-1 ms-auto pe-3">
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
                    'style' => 'width: 280px;'
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
