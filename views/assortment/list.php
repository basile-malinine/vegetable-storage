<?php

use yii\data\ActiveDataProvider;
use yii\grid\GridView;


/** @var ActiveDataProvider $dataProvider Данные */

$header = 'Номенклатура';

$this->registerJs('let controllerName = "assortment";', \yii\web\View::POS_HEAD);
$this->registerJsFile('@web/js/contextmenu-list.js');
?>

<div class="page-content">
    <div class="page-top-panel">
        <div class="page-top-panel-header d-flex">
            <?= $header ?>
            <?php if (Yii::$app->user->can('assortment.create')): ?>
            <a href="/assortment/create" class="btn btn-light btn-outline-secondary btn-sm mt-1 ms-auto pe-3">
                <i class="fa fa-plus"></i>
                <span class="ms-2">Добавить</span>
            </a>
            <?php endif ?>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,

        'rowOptions' => function ($model, $key, $index, $grid) {
            return [
                'class' => 'contextMenuRow',
                'data-row-id' => $model->id,
            ];
        },

        'columns' => [
            // Весовая (иконка)
            [
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->unit->is_weight ? '<i class="fas fa-balance-scale"></i>' : '';
                },
                'contentOptions' => [
                    'style' => 'color: #0077ff; text-align: center',
                ],
                'headerOptions' => [
                    'style' => 'width: 30px',
                ],
            ],

            // #
            [
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => [
                    'style' => 'width: 40px;'
                ],
                'contentOptions' => [
                    'style' => 'text-align: right;',
                ],
            ],

            // Название
            [
                'attribute' => 'name',
                'enableSorting' => false,
                'headerOptions' => [
                    'style' => 'width: 280px;'
                ],
                'filterInputOptions' => [
                    'class' => 'form-control form-control-sm',
                ],
            ],

            // Вес
            [
                'attribute' => 'weight',
                'enableSorting' => false,
                'headerOptions' => [
                    'style' => 'width: 80px;'
                ],
                'filterInputOptions' => [
                    'class' => 'form-control form-control-sm',
                ],
                'contentOptions' => [
                    'style' => 'text-align: right;',
                ],
            ],

            // Вес
            [
                'attribute' => 'pallet_weight',
                'label' => 'Вес<br>паллета',
                'encodeLabel' => false,
                'enableSorting' => false,
                'headerOptions' => [
                    'style' => 'width: 80px;'
                ],
                'filterInputOptions' => [
                    'class' => 'form-control form-control-sm',
                ],
                'contentOptions' => [
                    'style' => 'text-align: right;',
                ],
            ],

            // Базовый продукт (Возможно пригодится для Фасовки...)
//            [
//                'attribute' => 'product_id',
//                'value' => 'product.name',
//                'enableSorting' => false,
//                'headerOptions' => [
//                    'style' => 'width: 280px;'
//                ],
//                'filterInputOptions' => [
//                    'class' => 'form-control form-control-sm',
//                ],
//            ],

            // Комментарий
            [
                'attribute' => 'comment',
                'enableSorting' => false,
                'filterInputOptions' => [
                    'class' => 'form-control form-control-sm',
                ],
            ],
        ],
    ]); ?>
</div>
