<?php

/** @var yii\data\ActiveDataProvider $dataProviderItem */

/** @var Delivery $model */

use yii\grid\GridView;
use yii\widgets\Pjax;

use app\models\Documents\Delivery\Delivery;

?>

<?php Pjax::begin(['id' => 'delivery-items']) ?>
<?= GridView::widget([
    'layout' => "{items}\n{pager}",
    'dataProvider' => $dataProviderItem,

    'rowOptions' => $model->date_close ? [] : function ($model, $key, $index, $grid) {
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
            'label' => 'Номенклатура',
            'attribute' => 'assortment',
            'value' => 'assortment.name',
            'enableSorting' => false,
            'headerOptions' => [
                'style' => 'width: 180px;'
            ],
        ],

        // Отправлено
        [
            'format' => 'raw',
            'attribute' => 'shipped',
            'enableSorting' => false,
            'value' => function ($model) {
                return number_format($model->shipped, 0,
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

        // Общая стоимость
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
//        [
//            'format' => 'raw',
//            'attribute' => 'weight',
//            'enableSorting' => false,
//            'value' => function ($model) {
//                return number_format($model->weight, 0,
//                    '.', ' ');
//            },
//            'contentOptions' => [
//                'style' => 'text-align: right;'
//            ],
//            'headerOptions' => [
//                'style' => 'width: 100px;'
//            ],
//        ],

        // План по работе
        [
            'attribute' => 'work_plan',
            'enableSorting' => false,
            'filterInputOptions' => [
                'class' => 'form-control form-control-sm',
            ],
        ]
    ],
]); ?>
<?php Pjax::end() ?>
