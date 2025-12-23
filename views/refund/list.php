<?php

/** @var ActiveDataProvider $dataProvider Данные */

use app\models\Documents\Refund\Refund;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;

$header = 'Возвраты';

$this->registerJs('let controllerName = "refund";', \yii\web\View::POS_HEAD);
$this->registerJsFile('@web/js/contextmenu-list.js');
?>

<div class="page-content">
    <div class="page-top-panel">
        <div class="page-top-panel-header d-flex">
            <?= $header ?>
            <a href="/refund/create" class="btn btn-light btn-outline-secondary btn-sm mt-1 ms-auto pe-3">
                <i class="fa fa-plus"></i>
                <span class="ms-2">Добавить</span>
            </a>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,

        'rowOptions' => function (Refund $model, $key, $index, $grid) {
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

            // Тип возврата
            [
                'attribute' => 'type_id',
                'enableSorting' => false,
                'value' => function (Refund $model) {
                    return Refund::TYPE_LIST[$model->type_id];
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

            // Заказ
            [
                'format' => 'raw',
                'attribute' => 'order_id',
                'label' => 'По документу',
                'enableSorting' => false,
                'value' => 'order.label',
                'headerOptions' => [
                    'style' => 'width: 400px;'
                ],
            ],

            // Дата возврата
            [
                'attribute' => 'refund_date',
                'enableSorting' => false,
                'value' => function ($model) {
                    if ($model->refund_date == null) {
                        return '';
                    }
                    return date("d.m.Y", strtotime($model->refund_date));
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

            // Количество
            [
                'attribute' => 'quantity',
                'label' => 'Количество',
                'enableSorting' => false,
                'value' => function ($model) {
                    $val = $model->items[0]->quantity;
                    if ($model->items[0]->assortment->unit->is_weight) {
                        $val = number_format($val, 1, '.', ' ');
                    } else {
                        $val = number_format($val, 0, '.', ' ');
                    }
                    return $val . ' (' . $model->items[0]->assortment->unit->name . ')';
                },
                'headerOptions' => [
                    'style' => 'width: 100px;'
                ],
                'contentOptions' => [
                    'style' => 'text-align: right;'
                ],
                'filterInputOptions' => [
                    'class' => 'form-control form-control-sm',
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

            // Склад
            [
                'format' => 'raw',
                'attribute' => 'stock_id',
                'enableSorting' => false,
                'value' => 'stock.name',
                'headerOptions' => [
                    'style' => 'width: 120px;'
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
