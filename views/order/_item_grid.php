<?php

/** @var yii\data\ActiveDataProvider $dataProviderItem */
/** @var Order $model */

use yii\grid\GridView;
use yii\widgets\Pjax;

use app\models\Documents\Order\Order;

?>

<?php Pjax::begin(['id' => 'order-items']) ?>
<?= GridView::widget([
    'layout' => "{items}\n{pager}",
    'dataProvider' => $dataProviderItem,

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
            'format' => 'raw',
            'label' => 'Номенклатурная позиция',
            'attribute' => 'assortment',
            'value' => 'assortment.name',
            'enableSorting' => false,
            'filterInputOptions' => [
                'class' => 'form-control form-control-sm',
            ],
        ],

        // Количество
        [
            'format' => 'raw',
            'attribute' => 'quantity',
            'enableSorting' => false,
            'value' => function ($model) {
                return number_format($model->quantity, 1,
                        '.', ' ')
                    . ' (' . $model->assortment->unit->name . ')';
            },
            'contentOptions' => [
                'style' => 'text-align: right;'
            ],
            'headerOptions' => [
                'style' => 'width: 100px;'
            ],
        ],

        // Единица измерения
//        [
//            'format' => 'raw',
//            'attribute' => 'unit',
//            'label' => 'Изм',
//            'value' => 'assortment.unit.name',
//            'enableSorting' => false,
//            'contentOptions' => [
//                'style' => 'text-align: center;'
//            ],
//            'headerOptions' => [
//                'style' => 'width: 40px;'
//            ],
//        ],

        // Цена
        [
            'format' => 'raw',
            'attribute' => 'price',
            'enableSorting' => false,
            'value' => function ($model) {
                return number_format($model->price, 2,
                        '.', ' ');
            },
            'contentOptions' => [
                'style' => 'text-align: right;'
            ],
            'headerOptions' => [
                'style' => 'width: 100px;'
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
            'contentOptions' => [
                'style' => 'text-align: right;'
            ],
            'headerOptions' => [
                'style' => 'width: 100px;'
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
            'contentOptions' => [
                'style' => 'text-align: right;'
            ],
            'headerOptions' => [
                'style' => 'width: 100px;'
            ],
        ],

        // Количество (факт)  ------------------------------------------ на будущее, чтобы не забыть!!!
//        [
//            'format' => 'raw',
//            'attribute' => 'quantity_fact',
//            'enableSorting' => false,
//            'contentOptions' => [
//                'style' => 'text-align: right;'
//            ],
//            'headerOptions' => [
//                'style' => 'width: 100px;'
//            ],
//        ],

        // Сумма (факт)
//        [
//            'format' => 'raw',
//            'attribute' => 'price_total_fact',
//            'enableSorting' => false,
//            'contentOptions' => [
//                'style' => 'text-align: right;'
//            ],
//            'headerOptions' => [
//                'style' => 'width: 100px;'
//            ],
//        ],

        // Вес (факт)
//        [
//            'format' => 'raw',
//            'attribute' => 'weight_fact',
//            'enableSorting' => false,
//            'contentOptions' => [
//                'style' => 'text-align: right;'
//            ],
//            'headerOptions' => [
//                'style' => 'width: 100px;'
//            ],
//        ],
    ],
]); ?>
<?php Pjax::end() ?>
