<?php

/** @var yii\data\ActiveDataProvider $dataProviderItem */

/** @var Packing $model */

use yii\grid\GridView;
use yii\widgets\Pjax;

use app\models\Documents\Packing\Packing;
use app\models\Documents\Packing\PackingItem;
use app\models\PalletType\PalletType;

?>

<?php Pjax::begin(['id' => 'packing-item-grid']) ?>
<?= GridView::widget([
    'layout' => "{items}\n{pager}",
    'dataProvider' => $dataProviderItem,
    'rowOptions' => $model->date_close ? [] : function (PackingItem $model, $key, $index, $grid) {
        return [
            'class' => 'contextMenuRow',
            'data-row-id' => $model->packing_id . '/' . $model->acceptance_id,
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

        // Приёмка
        [
            'format' => 'raw',
            'label' => 'Приёмка',
            'attribute' => 'acceptance_id',
            'value' => function (PackingItem $model) {
                return $model->acceptance->shortLabel;
            },
            'enableSorting' => false,
            'headerOptions' => [
                'style' => 'width: 150px;'
            ],
        ],

        // Номенклатура
        [
            'format' => 'raw',
            'label' => 'Номенклатура',
            'attribute' => 'assortment',
            'value' => function (PackingItem $model) {
                return $model->acceptance->positionWithQualityName;
            },
            'enableSorting' => false,
            'headerOptions' => [
                'style' => 'width: 260px;'
            ],
        ],

        // Количество
        [
            'format' => 'raw',
            'attribute' => 'quantity',
            'label' => 'Кол-во',
            'enableSorting' => false,
            'value' => function (PackingItem $model) {
                return number_format($model->quantity, 0,
                        '.', ' ')
                    . ' (' . $model->acceptance->items[0]->assortment->unit->name . ')';
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
            'value' => function (PackingItem $model) {
                $position = $model->acceptance->items[0];
                return $position->pallet_type_id && $model
                    ? PalletType::findOne($position->pallet_type_id)->name
                    : null;
            },
            'headerOptions' => [
                'style' => 'width: 120px;'
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

        // Вес
        [
            'format' => 'raw',
            'attribute' => 'weight',
            'enableSorting' => false,
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
