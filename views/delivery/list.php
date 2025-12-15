<?php

/** @var ActiveDataProvider $dataProvider Данные */

use app\models\Documents\Delivery\Delivery;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;

$header = 'Поставки';

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
            // Тип Через исполнителя (иконка)
            [
                'format' => 'raw',
                'value' => function (Delivery $model) {
                    return $model->type_id === Delivery::TYPE_EXECUTOR
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
                'attribute' => 'shipment_date',
                'enableSorting' => false,
                'value' => function ($model) {
                    if ($model->shipment_date == null) {
                        return '';
                    }
                    return date("d.m.Y", strtotime($model->shipment_date));
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
                'value' => function (Delivery $model, $key, $index, $grid) {
                    $val = '';
                    switch ($model->type_id) {
                        case Delivery::TYPE_STOCK:
                            $val = '<span style="padding-left: 14px">'
                                . $model->stock->name . '</span>';
                            break;
                        case Delivery::TYPE_EXECUTOR:
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
            [
                'format' => 'raw',
                'attribute' => 'price_total',
                'enableSorting' => false,
                'value' => function ($model) {
                    return number_format($model->price_total, 0,
                            '.', ' ');
                },
                'headerOptions' => [
                    'style' => 'width: 120px;'
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
