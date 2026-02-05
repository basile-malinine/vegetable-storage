<?php

/** @var yii\data\ActiveDataProvider $dataProviderItem */
/** @var Increase $model */

use yii\grid\GridView;
use yii\widgets\Pjax;

use app\models\Documents\Increase\IncreaseItem;
use app\models\Documents\Increase\Increase;
use app\models\PalletType\PalletType;

?>

<?php Pjax::begin(['id' => 'increase-items']) ?>
<?= GridView::widget([
    'layout' => "{items}\n{pager}",
    'dataProvider' => $dataProviderItem,
    'rowOptions' => $model->date_close ? [] : function (IncreaseItem $model, $key, $index, $grid) {
        return [
            'class' => 'contextMenuRow',
            'data-row-id' => $model->increase_id . '/' . $model->assortment_id,
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
