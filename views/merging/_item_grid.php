<?php

/** @var yii\data\ActiveDataProvider $dataProviderItem */

/** @var Merging $model */

use yii\grid\GridView;
use yii\widgets\Pjax;

use app\models\Documents\Merging\Merging;
use app\models\Documents\Merging\MergingItem;
use app\models\PalletType\PalletType;

?>

<?php Pjax::begin(['id' => 'merging-item-grid']) ?>
<?= GridView::widget([
    'layout' => "{items}\n{pager}",
    'dataProvider' => $dataProviderItem,
    'rowOptions' => $model->date_close ? [] : function (MergingItem $model, $key, $index, $grid) {
        return [
            'class' => 'contextMenuRow',
            'data-row-id' => $model->merging_id . '/' . $model->acceptance_id,
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
            'value' => function (MergingItem $model) {
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
            'value' => function (MergingItem $model) {
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
            'value' => function (MergingItem $model) {
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
            'label' => 'Тип паллет',
            'enableSorting' => false,
            'value' => function (MergingItem $model) {
                $position = $model->acceptance->items[0];
                return $position->pallet_type_id && $model
                    ? PalletType::findOne($position->pallet_type_id)->name
                    : null;
            },
            'headerOptions' => [
                'style' => 'width: 160px;'
            ],
        ],

        // Количество паллет
        [
            'format' => 'raw',
            'attribute' => 'quantity_pallet',
            'label' => 'Кол-во паллет',
            'enableSorting' => false,
            'value' => function (MergingItem $model) {
                return $model->quantity_pallet;
            },
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
            'value' => function (MergingItem $model) {
                return $model->quantity_paks;
            },
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
