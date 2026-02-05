<?php

/** @var ActiveDataProvider $dataProvider Данные */

use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\web\View;

use app\models\Documents\Decrease\Decrease;

$header = 'Списание';

$this->registerJs('let controllerName = "decrease";', View::POS_HEAD);
$this->registerJsFile('@web/js/contextmenu-list.js');
?>

<div class="page-content">
    <div class="page-top-panel">
        <div class="page-top-panel-header d-flex">
            <?= $header ?>
            <a href="/decrease/create" class="btn btn-light btn-outline-secondary btn-sm mt-1 ms-auto pe-3">
                <i class="fa fa-plus"></i>
                <span class="ms-2">Добавить</span>
            </a>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,

        'rowOptions' => function (Decrease $model, $key, $index, $grid) {
            return [
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

            // Дата списания
            [
                'attribute' => 'date',
                'label' => 'Дата',
                'enableSorting' => false,
                'value' => function ($model) {
                    return date("d.m.Y", strtotime($model->date));
                },
                'headerOptions' => [
                    'style' => 'width: 100px;'
                ],
                'filterInputOptions' => [
                    'class' => 'form-control form-control-sm',
                ],
            ],

            // Предприятие
            [
                'attribute' => 'company_own_id',
                'enableSorting' => false,
                'value' => 'companyOwn.name',
                'headerOptions' => [
                    'style' => 'width: 180px; text-align: center;'
                ],
                'filterInputOptions' => [
                    'class' => 'form-control form-control-sm',
                ],
            ],

            // Склад
            [
                'attribute' => 'stock_id',
                'enableSorting' => false,
                'value' => 'stock.name',
                'headerOptions' => [
                    'style' => 'width: 100px; text-align: center;'
                ],
                'filterInputOptions' => [
                    'class' => 'form-control form-control-sm',
                ],
            ],

            // Номенклатура
            [
                'attribute' => 'assortment',
                'label' => 'Номенклатура',
                'enableSorting' => false,
                'value' => function (Decrease $model) {
                    return $model->assortments[0]->name;
                },
                'headerOptions' => [
                    'style' => 'width: 180px; text-align: center;'
                ],
                'filterInputOptions' => [
                    'class' => 'form-control form-control-sm',
                ],
            ],

            // Количество
            [
                'attribute' => 'quantity',
                'label' => 'Количество',
                'enableSorting' => false,
                'value' => function (Decrease $model) {
                    $val = $model->items[0]->quantity;
                    if ($model->assortments[0]->unit->is_weight) {
                        $val = number_format($val, 1, '.', ' ');
                    } else {
                        $val = number_format($val, 0, '.', ' ');
                    }
                    return $val . ' (' . $model->assortments[0]->unit->name . ')';
                },
                'headerOptions' => [
                    'style' => 'width: 100px;'
                ],
                'contentOptions' => [
                    'style' => 'text-align: right;'
                ],
            ],

            // Списано
            [
                'attribute' => 'decreased',
                'label' => 'Списано',
                'enableSorting' => false,
                'value' => function (Decrease $model) {
                    $val = $model->items[0]->quantity;
                    if ($model->assortments[0]->unit->is_weight) {
                        $val = number_format($val, 1, '.', ' ');
                    } else {
                        $val = number_format($val, 0, '.', ' ');
                    }

                    return $model->date_close ? $val . ' (' . $model->assortments[0]->unit->name . ')' : '';
                },
                'headerOptions' => [
                    'style' => 'width: 100px;'
                ],
                'contentOptions' => [
                    'style' => 'text-align: right;'
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
