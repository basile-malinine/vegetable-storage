<?php

/** @var ActiveDataProvider $dataProvider Данные */

use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\web\View;

use app\models\Documents\Moving\Moving;

$header = 'Перемещения';

$this->registerJs('let controllerName = "moving";', View::POS_HEAD);
$this->registerJsFile('@web/js/contextmenu-list.js');
?>

<div class="page-content">
    <div class="page-top-panel">
        <div class="page-top-panel-header d-flex">
            <?= $header ?>
            <a href="/moving/create" class="btn btn-light btn-outline-secondary btn-sm mt-1 ms-auto pe-3">
                <i class="fa fa-plus"></i>
                <span class="ms-2">Добавить</span>
            </a>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,

        'rowOptions' => function (Moving $model, $key, $index, $grid) {
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

            // Дата перемещения
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

            // Отправитель
            [
                'attribute' => 'company_sender_id',
                'enableSorting' => false,
                'value' => 'companySender.name',
                'headerOptions' => [
                    'style' => 'width: 180px; text-align: center;'
                ],
                'filterInputOptions' => [
                    'class' => 'form-control form-control-sm',
                ],
            ],

            // Со склада
            [
                'attribute' => 'stock_sender_id',
                'enableSorting' => false,
                'value' => 'stockSender.name',
                'headerOptions' => [
                    'style' => 'width: 100px; text-align: center;'
                ],
                'filterInputOptions' => [
                    'class' => 'form-control form-control-sm',
                ],
            ],

            // Получатель
            [
                'attribute' => 'company_recipient_id',
                'enableSorting' => false,
                'value' => 'companyRecipient.name',
                'headerOptions' => [
                    'style' => 'width: 180px; text-align: center;'
                ],
                'filterInputOptions' => [
                    'class' => 'form-control form-control-sm',
                ],
            ],

            // На склад
            [
                'attribute' => 'stock_recipient_id',
                'enableSorting' => false,
                'value' => 'stockRecipient.name',
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
                'value' => function (Moving $model) {
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
                'value' => function (Moving $model) {
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
