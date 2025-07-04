<?php

use yii\data\ActiveDataProvider;
use yii\grid\GridView;


/** @var string $header Заголовок */
/** @var ActiveDataProvider $dataProvider Данные */

$this->registerJs('let controllerName = "unit";', \yii\web\View::POS_HEAD);
$this->registerJsFile('@web/js/contextmenu-list.js');
?>

<div class="page-content">
    <div class="page-top-panel">
        <div class="page-top-panel-header d-flex">
            <?= $header ?>
            <a href="/unit/create" class="btn btn-light btn-outline-secondary btn-sm mt-1 ms-auto pe-3">
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
            // Весовая (иконка)
            [
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->is_weight ? '<i class="fas fa-balance-scale"></i>' : '';
                },
                'contentOptions' => [
                    'style' => 'color: #0077ff; text-align: center',
                ],
                'headerOptions' => [
                    'style' => 'width: 30px',
                ],
            ],

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

            // Вес
            [
                'attribute' => 'weight',
                'enableSorting' => false,
                'headerOptions' => [
                    'style' => 'width: 180px;'
                ],
                'filterInputOptions' => [
                    'class' => 'form-control form-control-sm',
                ],
            ],

            // Пустота
            [
            ]

        ],
    ]); ?>
</div>
