<?php

/** @var ActiveDataProvider $dataProvider Данные */

use app\models\Documents\Remainder\Remainder;
use app\models\PalletType\PalletType;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;

$header = 'Остатки';
?>

<div class="page-content">
    <div class="page-top-panel">
        <div class="page-top-panel-header d-flex">
            <?= $header ?>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,

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
                'attribute' => 'acceptance_id',
                'enableSorting' => false,
                'value' => 'acceptance.label',
                'headerOptions' => [
                    'style' => 'width: 280px; text-align: center;'
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
                    'style' => 'width: 180px;'
                ],
            ],

            // Склад
            [
                'format' => 'raw',
                'attribute' => 'stock_id',
                'enableSorting' => false,
                'value' => 'stock.name',
                'headerOptions' => [
                    'style' => 'width: 100px;'
                ],
            ],

            // Номенклатура
            [
                'format' => 'raw',
                'attribute' => 'assortment',
                'label' => 'Номенклатура',
                'enableSorting' => false,
                'value' => function (Remainder $model) {
                    $quality = $model->acceptance->items[0]->quality;
                    $qualityName = $quality ? ' (' . $quality->name . ')' : '';

                    return $model->assortment->name . $qualityName;
                },
                'headerOptions' => [
                    'style' => 'width: 220px;'
                ],
            ],

            // Количество
            [
                'format' => 'html',
                'attribute' => 'quantity',
                'value' => function (Remainder $model) {
                    $qnt = $model->quantity;
                    if ((int) $qnt) {
                        $qnt = number_format($qnt, 0, '.', ' ');
                    } else {
                        $qnt = '';
                    }
                    $free = Remainder::getFreeByAcceptance($model->acceptance_id, 'quantity');
                    if ((int) $free) {
                        $free = number_format($free, 0, '.', ' ');
                    } elseif($qnt === '') {
                        $free = '';
                    }
                    $val = '<span>' . $qnt . '</span><br>'
                        . '<span style="color: #0046ff">' . $free . '</span>';

                    return $val;
                },
                'enableSorting' => false,
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

            // Тип паллет
            [
                'attribute' => 'pallet_type_id',
                'value' => function (Remainder $model) {
                    if ($model->pallet_type_id && $model->quantity_pallet > 0) {
                        $val = PalletType::findOne($model->pallet_type_id)->name;
                    } else {
                        $val = '';
                    }

                    return $val;
                },
                'enableSorting' => false,
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

            // Количество паллет
            [
                'format' => 'html',
                'attribute' => 'quantity_pallet',
                'value' => function (Remainder $model) {
                    $qnt = $model->quantity_pallet;
                    $qnt = $qnt ? $qnt : '';
                    $free = Remainder::getFreeByAcceptance($model->acceptance_id, 'quantity_pallet');
                    $free = $qnt ? $free : '';

                    return '<span>' . $qnt . '</span><br>' . '<span style="color: #0046ff">' . $free . '</span>';
                },
                'enableSorting' => false,
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

            // Количество тары
            [
                'format' => 'html',
                'attribute' => 'quantity_paks',
                'value' => function (Remainder $model) {
                    $qnt = $model->quantity_paks;
                    $qnt = $qnt ? $qnt : '';
                    $free = Remainder::getFreeByAcceptance($model->acceptance_id, 'quantity_paks');
                    $free = $qnt ? $free : '';

                    return '<span>' . $qnt . '</span><br>' . '<span style="color: #0046ff">' . $free . '</span>';
                },
                'enableSorting' => false,
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
