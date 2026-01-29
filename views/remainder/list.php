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
            <a href="/refund/create" class="btn btn-light btn-outline-secondary btn-sm mt-1 ms-auto pe-3">
                <i class="fa fa-plus"></i>
                <span class="ms-2">Добавить</span>
            </a>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,

        'columns' => [
            // № (ID)
//            [
//                'attribute' => 'id',
//                'label' => '№',
//                'enableSorting' => false,
//                'contentOptions' => [
//                    'style' => 'text-align: right;'
//                ],
//                'headerOptions' => [
//                    'style' => 'width: 50px;'
//                ],
//            ],

            // Приёмка
            [
                'attribute' => 'acceptance_id',
                'enableSorting' => false,
                'value' => 'acceptance.label',
                'headerOptions' => [
                    'style' => 'width: 280px; text-align: center;'
                ],
                'contentOptions' => [
                    'style' => 'text-align: center;'
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
                    return $model->assortment->name;
                },
                'headerOptions' => [
                    'style' => 'width: 220px;'
                ],
            ],

            // Количество
            [
                'attribute' => 'quantity',
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
                'value' => function ($model) {
                    if ($model->pallet_type_id) {
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
                'attribute' => 'quantity_pallet',
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
                'attribute' => 'quantity_paks',
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
