<?php

/** @var Shipment $model */

/** @var ActiveDataProvider $dataProvider Данные */

use yii\data\ActiveDataProvider;
use yii\grid\GridView;

use app\models\Documents\Shipment\Shipment;

$header = 'Отгрузки';

$this->registerJs('let controllerName = "shipment";', \yii\web\View::POS_HEAD);
$this->registerJsFile('@web/js/contextmenu-list.js');
?>

<div class="page-content">
    <div class="page-top-panel">
        <div class="page-top-panel-header d-flex">
            <?= $header ?>
            <a href="/shipment/create" class="btn btn-light btn-outline-secondary btn-sm mt-1 ms-auto pe-3">
                <i class="fa fa-plus"></i>
                <span class="ms-2">Добавить</span>
            </a>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,

        'rowOptions' => function (Shipment $model, $key, $index, $grid) {
            // Если Отгрузка по Перемещению, контекстное меню в списке Отгрузок отключено
            return $model->type_id === Shipment::TYPE_MOVING
            || $model->type_id === Shipment::TYPE_DECREASE
            || $model->type_id === Shipment::TYPE_SORTING
            || $model->type_id === Shipment::TYPE_MERGING
            || $model->type_id === Shipment::TYPE_PACKING
                ? []
                : [
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

            // Дата отгрузки
            [
                'attribute' => 'date',
                'label' => 'Дата',
                'enableSorting' => false,
                'value' => function ($model) {
                    if ($model->date == null) {
                        return '';
                    }
                    return date("d.m.Y", strtotime($model->date));
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

            // Тип отгрузки
            [
                'attribute' => 'type_id',
                'label' => 'Тип',
                'enableSorting' => false,
                'value' => function (Shipment $model) {
                    return Shipment::TYPE_LIST[$model->type_id];
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

            // По документу
            [
                'format' => 'raw',
                'attribute' => 'parent_doc_id',
                'label' => 'Документ',
                'enableSorting' => false,
                'value' => function (Shipment $model) {
                    return $model->parentDoc->label;
                },
                'headerOptions' => [
                    'style' => 'width: 400px;'
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
                    'style' => 'width: 180px;'
                ],
            ],

            // Поставка
            [
                'attribute' => 'delivery_id',
                'enableSorting' => false,
                'value' => 'delivery.shortLabel',
                'headerOptions' => [
                    'style' => 'width: 100px;'
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
