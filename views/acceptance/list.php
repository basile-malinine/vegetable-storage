<?php

/** @var ActiveDataProvider $dataProvider Данные */

use app\models\Documents\Acceptance\Acceptance;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;

$header = 'Приёмки';

$this->registerJs('let controllerName = "acceptance";', \yii\web\View::POS_HEAD);
$this->registerJsFile('@web/js/contextmenu-list.js');
?>

<div class="page-content">
    <div class="page-top-panel">
        <div class="page-top-panel-header d-flex">
            <?= $header ?>
            <a href="/acceptance/create" class="btn btn-light btn-outline-secondary btn-sm mt-1 ms-auto pe-3">
                <i class="fa fa-plus"></i>
                <span class="ms-2">Добавить</span>
            </a>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,

        'rowOptions' => function (Acceptance $model, $key, $index, $grid) {
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

            // Тип приёмки
            [
                'attribute' => 'type_id',
                'enableSorting' => false,
                'value' => function (Acceptance $model) {
                    return Acceptance::TYPE_LIST[$model->type_id];
                },
                'headerOptions' => [
                    'style' => 'width: 100px; text-align: center;'
                ],
                'contentOptions' => [
                    'style' => 'text-align: center;'
                ],
                'filterInputOptions' => [
                    'class' => 'form-control form-control-sm',
                ],
            ],

            // По документу
            [
                'format' => 'raw',
                'attribute' => 'parent_doc_id',
                'label' => 'По документу',
                'enableSorting' => false,
                'value' => 'parentDoc.label',
                'headerOptions' => [
                    'style' => 'width: 400px;'
                ],
            ],

            // Дата приёмки
            [
                'attribute' => 'acceptance_date',
                'enableSorting' => false,
                'value' => function ($model) {
                    if ($model->acceptance_date == null) {
                        return '';
                    }
                    return date("d.m.Y", strtotime($model->acceptance_date));
                },
                'headerOptions' => [
                    'style' => 'width: 100px;'
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
                    'style' => 'width: 200px;'
                ],
            ],

            // Склад
            [
                'format' => 'raw',
                'attribute' => 'stock_id',
                'enableSorting' => false,
                'value' => 'stock.name',
                'headerOptions' => [
                    'style' => 'width: 180px;'
                ],
            ],

            // Поставка
            [
                'attribute' => 'delivery_id',
                'enableSorting' => false,
                'value' => 'delivery.shortLabel',
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
</div>
