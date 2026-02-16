<?php

/** @var ActiveDataProvider $dataProvider Данные */

use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\web\View;

use app\models\Documents\Merging\Merging;

$header = 'Объединение';

$this->registerJs('let controllerName = "merging";', View::POS_HEAD);
$this->registerJsFile('@web/js/contextmenu-list.js');
?>

<div class="page-content">
    <div class="page-top-panel">
        <div class="page-top-panel-header d-flex">
            <?= $header ?>
            <a href="/merging/create" class="btn btn-light btn-outline-secondary btn-sm mt-1 ms-auto pe-3">
                <i class="fa fa-plus"></i>
                <span class="ms-2">Добавить</span>
            </a>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,

        'rowOptions' => function (Merging $model, $key, $index, $grid) {
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

            // Дата
            [
                'attribute' => 'date',
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
                'attribute' => 'assortment_id',
                'enableSorting' => false,
                'value' => 'assortment.name',
                'headerOptions' => [
                    'style' => 'width: 180px; text-align: center;'
                ],
                'filterInputOptions' => [
                    'class' => 'form-control form-control-sm',
                ],
            ],

            // Количество
            [
                'format' => 'html',
                'attribute' => 'quantity',
                'enableSorting' => false,
                'value' => function (Merging $model) {
                    $val = $model->quantity ?? 0;
                    if ($model->assortment->unit->is_weight) {
                        $val = number_format($val, 1, '.', ' ');
                    } else {
                        $val = number_format($val, 0, '.', ' ');
                    }
                    $val .= ' (' . $model->assortment->unit->name . ')';
                    if (!$model->items || count($model->items) < 2) {
                        return '<span style="color: red; font-weight: bold;">' . $val . '</span>';
                    }

                    return $val;
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
                'format' => 'html',
                'attribute' => 'comment',
                'enableSorting' => false,
                'value' => function (Merging $model) {
                    if (!$model->items || count($model->items) < 2) {
                        return '<span style="color: red; font-weight: bold;">В Объединении недостаточно приёмок</span>';
                    }

                    return $model->comment;
                },
                'filterInputOptions' => [
                    'class' => 'form-control form-control-sm',
                ],
            ]
        ],
    ]); ?>
</div>
