<?php

/** @var yii\data\ActiveDataProvider $dataProviderItem */
/** @var Sorting $model */

use yii\grid\GridView;
use yii\widgets\Pjax;

use app\models\Documents\Sorting\SortingItem;
use app\models\Documents\Sorting\Sorting;
use app\models\PalletType\PalletType;

?>

<?php Pjax::begin(['id' => 'sorting-items']) ?>
<?= GridView::widget([
    'layout' => "{items}\n{pager}",
    'dataProvider' => $dataProviderItem,
    'rowOptions' => $model->date_close ? [] : function (SortingItem $model, $key, $index, $grid) {
        return [
            'class' => 'contextMenuRow',
            'data-row-id' => $model->sorting_id . '/' . $model->assortment_id,
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

        // Номенклатура
        [
            'format' => 'raw',
            'label' => 'Номенклатура',
            'attribute' => 'assortment',
            'value' => 'assortment.name',
            'enableSorting' => false,
            'headerOptions' => [
                'style' => 'width: 260px;'
            ],
        ],

        // Качество
        [
            'attribute' => 'quality',
            'label' => 'Качество',
            'enableSorting' => false,
            'value' => function (SortingItem $model) {
                return $model->quality ? $model->quality->name : 'Не назначено';
            },
            'headerOptions' => [
                'style' => 'width: 100px; text-align: center;'
            ],
            'filterInputOptions' => [
                'class' => 'form-control form-control-sm',
            ],
        ],

        // Количество
        [
            'format' => 'raw',
            'attribute' => 'quantity',
            'label' => 'Кол-во',
            'enableSorting' => false,
            'value' => function ($model) {
                return number_format($model->quantity, 0,
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

        // Тип паллет
        [
            'format' => 'raw',
            'attribute' => 'pallet_type_id',
            'enableSorting' => false,
            'value' => function ($model) {
                return $model->pallet_type_id ? PalletType::findOne($model->pallet_type_id)->name : null;
            },
            'headerOptions' => [
                'style' => 'width: 100px;'
            ],
        ],

        // Количество паллет
        [
            'format' => 'raw',
            'attribute' => 'quantity_pallet',
            'label' => 'Кол-во паллет',
            'enableSorting' => false,
            'contentOptions' => [
                'style' => 'text-align: right;'
            ],
            'headerOptions' => [
                'style' => 'width: 100px;'
            ],
        ],

        // Количество тары
        [
            'format' => 'raw',
            'attribute' => 'quantity_paks',
            'label' => 'Кол-во тары',
            'enableSorting' => false,
            'contentOptions' => [
                'style' => 'text-align: right;'
            ],
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
<?php Pjax::end() ?>
