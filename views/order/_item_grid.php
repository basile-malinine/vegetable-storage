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
            'data-row-id' => $model->order_id . '/' . $model->assortment_id,
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
            'headerOptions' => [
                'style' => 'width: 200px;'
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
                'style' => 'width: 90px;'
            ],
        ],

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
                'style' => 'width: 70px;'
            ],
        ],

        // Сумма
//        [
//            'format' => 'raw',
//            'attribute' => 'price_total',
//            'enableSorting' => false,
//            'value' => function ($model) {
//                return number_format($model->price_total, 0,
//                        '.', ' ');
//            },
//            'contentOptions' => [
//                'style' => 'text-align: right;'
//            ],
//            'headerOptions' => [
//                'style' => 'width: 80px;'
//            ],
//        ],

        // Вес
//        [
//            'format' => 'raw',
//            'attribute' => 'weight',
//            'enableSorting' => false,
//            'value' => function ($model) {
//                return number_format($model->weight, 0,
//                        '.', ' ');
//            },
//            'contentOptions' => [
//                'style' => 'text-align: right;'
//            ],
//            'headerOptions' => [
//                'style' => 'width: 70px;'
//            ],
//        ],

        // Отгружено
        [
            'format' => 'raw',
            'attribute' => 'shipped',
            'enableSorting' => false,
            'value' => function ($model) {
                return $model->shipped
                    ? number_format($model->shipped, 1, '.', ' ')
                    : '';
            },
            'contentOptions' => [
                'style' => 'text-align: right;'
            ],
            'headerOptions' => [
                'style' => 'width: 80px;'
            ],
        ],

        // Принято РЦ
        [
            'format' => 'raw',
            'attribute' => 'accepted_dist_center',
            'enableSorting' => false,
            'value' => function ($model) {
                return $model->accepted_dist_center
                    ? number_format($model->accepted_dist_center, 1, '.', ' ')
                    : '';
            },
            'contentOptions' => [
                'style' => 'text-align: right;'
            ],
            'headerOptions' => [
                'style' => 'width: 80px;'
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
<?php Pjax::end() ?>
