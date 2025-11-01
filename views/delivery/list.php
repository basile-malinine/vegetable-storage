<?php

/** @var ActiveDataProvider $dataProvider Данные */

use yii\data\ActiveDataProvider;
use yii\grid\GridView;

$header = 'Доставки';

$this->registerJs('let controllerName = "delivery";', \yii\web\View::POS_HEAD);
$this->registerJsFile('@web/js/contextmenu-list.js');
?>

<div class="page-content">
    <div class="page-top-panel">
        <div class="page-top-panel-header d-flex">
            <?= $header ?>
            <a href="/delivery/create" class="btn btn-light btn-outline-secondary btn-sm mt-1 ms-auto pe-3">
                <i class="fa fa-plus"></i>
                <span class="ms-2">Добавить</span>
            </a>
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
            // № (ID)
            [
                'attribute' => 'id',
                'label' => '№',
                'enableSorting' => false,
                'contentOptions' => [
                    'style' => 'text-align: right;'
                ],
                'headerOptions' => [
                    'style' => 'width: 50px;'
                ],
            ],

            // Дата
            [
                'attribute' => 'created_at',
                'label' => 'Дата',
                'enableSorting' => false,
                'value' => function ($model) {
                    return date("d.m.Y", strtotime($model->created_at));
                },
                'headerOptions' => [
                    'style' => 'width: 100px; text-align: center;'
                ],
                'contentOptions' => [
                    'style' => 'text-align: center;'
                ],
                'filterInputOptions' => [
                    'class' => 'form-control form-control-sm',
                ],
            ],

            // Дата доставки
            [
                'attribute' => 'date_wait',
                'enableSorting' => false,
                'value' => function ($model) {
                    if ($model->date_wait == null) {
                        return '';
                    }
                    return date("d.m.Y", strtotime($model->date_wait));
                },
                'headerOptions' => [
                    'style' => 'width: 100px;'
                ],
                'contentOptions' => [
                    'style' => 'text-align: center;'
                ],
                'filterInputOptions' => [
                    'class' => 'form-control form-control-sm',
                ],
            ],

            // Поставщик
            [
                'attribute' => 'supplier_id',
                'enableSorting' => false,
                'value' => 'supplier.name',
                'headerOptions' => [
                    'style' => 'width: 170px;'
                ],
            ],

            // Предприятие
            [
                'format' => 'raw',
                'attribute' => 'own_id',
                'enableSorting' => false,
                'value' => function ($model) {
                    return $model->own->name . '<br>'
                        . $model->stock->name . '<br>'
                        . $model->manager->name;
                },
                'headerOptions' => [
                    'style' => 'width: 200px;'
                ],
            ],

            // Сумма
            [
                'format' => 'raw',
                'attribute' => 'price',
                'enableSorting' => false,
                'value' => function ($model) {
                    return number_format($model->price, 0,
                            '.', ' ');
                },
                'headerOptions' => [
                    'style' => 'width: 120px;'
                ],
                'contentOptions' => [
                    'style' => 'text-align: right;'
                ],
            ],

            // Вес
            [
                'format' => 'raw',
                'attribute' => 'weight',
                'enableSorting' => false,
                'value' => function ($model) {
                    return number_format($model->weight, 0,
                            '.', ' ');
                },
                'headerOptions' => [
                    'style' => 'width: 120px;'
                ],
                'contentOptions' => [
                    'style' => 'text-align: right;'
                ],
            ],

            // Сумма (факт)  ------------------------------------------ на будущее, чтобы не забыть!!!
//            [
//                'format' => 'raw',
//                'attribute' => 'price_fact',
//                'enableSorting' => false,
//                'value' => function (Delivery $model) {
//                    return number_format($model->price_fact, 0, '.', ' ') . ' (руб)';
//                },
//                'headerOptions' => [
//                    'style' => 'width: 120px;'
//                ],
//                'contentOptions' => [
//                    'style' => 'text-align: right;'
//                ],
//            ],

            // Вес (факт)
//            [
//                'format' => 'raw',
//                'attribute' => 'weight_fact',
//                'enableSorting' => false,
//                'value' => function (Delivery $model) {
//                    return number_format($model->weight_fact, 0, '.', ' ') . ' (кг)';;
//                },
//                'headerOptions' => [
//                    'style' => 'width: 120px;'
//                ],
//                'contentOptions' => [
//                    'style' => 'text-align: right;'
//                ],
//            ],

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
