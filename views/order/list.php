<?php

/** @var ActiveDataProvider $dataProvider Данные */

use app\models\Documents\Order\Order;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\web\View;

$header = 'Заказы';

$this->registerJs('let controllerName = "order";', View::POS_HEAD);
$this->registerJsFile('@web/js/contextmenu-list.js');
?>

<div class="page-content">
    <div class="page-top-panel">
        <div class="page-top-panel-header d-flex">
            <?= $header ?>
            <a href="/order/create" class="btn btn-light btn-outline-secondary btn-sm mt-1 ms-auto pe-3">
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
            // Тип Через исполнителя (иконка)
            [
                'format' => 'raw',
                'value' => function (Order $model) {
                    return $model->type_id === Order::TYPE_EXECUTOR
                        ? '<i class="fas fa-arrows-h"></i>' : '';
                },
                'contentOptions' => [
                    'style' => 'color: #0077ff; text-align: center',
                ],
                'headerOptions' => [
                    'style' => 'width: 30px',
                ],
            ],

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
                'attribute' => 'date',
                'label' => 'Дата',
                'enableSorting' => false,
                'value' => function ($model) {
                    return date("d.m.Y", strtotime($model->created_at));
                },
                'headerOptions' => [
                    'style' => 'width: 80px; text-align: center;'
                ],
                'contentOptions' => [
                    'style' => 'text-align: center;'
                ],
                'filterInputOptions' => [
                    'class' => 'form-control form-control-sm',
                ],
            ],

            // Покупатель (Сеть)
            [
                'attribute' => 'buyer_id',
                'enableSorting' => false,
                'value' => 'buyer.name',
                'headerOptions' => [
                    'style' => 'width: 200px;'
                ],
            ],

            // Распределительный центр
            [
                'format' => 'raw',
                'label' => 'РЦ',
                'attribute' => 'distribution_center_id',
                'enableSorting' => false,
                'value' => 'distributionCenter.name',
                'headerOptions' => [
                    'style' => 'width: 80px;'
                ],
            ],

            // Предприятие
            [
                'format' => 'raw',
                'attribute' => 'company_own_id',
                'enableSorting' => false,
                'value' => 'companyOwn.name',
                'headerOptions' => [
                    'style' => 'width: 200px;'
                ],
            ],

            // Склад / Исполнитель
            [
                'format' => 'raw',
                'attribute' => 'stock_executor',
                'enableSorting' => false,
                'value' => function (Order $model, $key, $index, $grid) {
                    $val = '';
                    switch ($model->type_id) {
                        case Order::TYPE_STOCK:
                            $val = '<span style="padding-left: 14px">'
                                . $model->stock->name . '</span>';
                            break;
                        case Order::TYPE_EXECUTOR:
                            $val = '<i class="fas fa-male me-1" style="color: #0077ff; margin-left: -5px;"></i>'
                                . $model->executor->name;
                            break;
                    }

                    return $val;
                },
                'headerOptions' => [
                    'style' => 'width: 180px;'
                ],
            ],

            // Сумма
//            [
//                'format' => 'raw',
//                'attribute' => 'price',
//                'enableSorting' => false,
//                'value' => function ($model) {
//                    return number_format($model->price, 0,
//                        '.', ' ');
//                },
//                'headerOptions' => [
//                    'style' => 'width: 100px;'
//                ],
//                'contentOptions' => [
//                    'style' => 'text-align: right;'
//                ],
//            ],

            // Вес
//            [
//                'format' => 'raw',
//                'attribute' => 'weight',
//                'enableSorting' => false,
//                'value' => function ($model) {
//                    return number_format($model->weight, 0,
//                        '.', ' ');
//                },
//                'headerOptions' => [
//                    'style' => 'width: 100px;'
//                ],
//                'contentOptions' => [
//                    'style' => 'text-align: right;'
//                ],
//            ],

            // Отгружено
            [
                'format' => 'raw',
                'attribute' => 'shipped',
                'enableSorting' => false,
                'headerOptions' => [
                    'style' => 'width: 110px;'
                ],
                'contentOptions' => [
                    'style' => 'text-align: right;'
                ],
            ],

            // Принято РЦ
            [
                'format' => 'raw',
                'attribute' => 'accepted_dist_center',
                'enableSorting' => false,
                'headerOptions' => [
                    'style' => 'width: 110px;'
                ],
                'contentOptions' => [
                    'style' => 'text-align: right;'
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
