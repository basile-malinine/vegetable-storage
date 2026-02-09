<?php

/** @var ActiveDataProvider $dataProvider Данные */

use app\models\Documents\Sorting\Sorting;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\web\View;

use app\models\Documents\Moving\Moving;

$header = 'Переборка';

$session = Yii::$app->session;
if ($session->has('old_values')) {
    $session->remove('old_values');
}

$this->registerJs('let controllerName = "sorting";', View::POS_HEAD);
$this->registerJsFile('@web/js/contextmenu-list.js');
?>

<div class="page-content">
    <div class="page-top-panel">
        <div class="page-top-panel-header d-flex">
            <?= $header ?>
            <a href="/sorting/create" class="btn btn-light btn-outline-secondary btn-sm mt-1 ms-auto pe-3">
                <i class="fa fa-plus"></i>
                <span class="ms-2">Добавить</span>
            </a>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,

        'rowOptions' => function (Sorting $model, $key, $index, $grid) {
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
                    'style' => 'width: 80px;'
                ],
                'filterInputOptions' => [
                    'class' => 'form-control form-control-sm',
                ],
            ],

            // Приёмка
            [
                'attribute' => 'acceptance',
                'label' => 'Приёмка',
                'enableSorting' => false,
                'value' => 'acceptance.shortLabel',
                'headerOptions' => [
                    'style' => 'width: 130px; text-align: center;'
                ],
                'filterInputOptions' => [
                    'class' => 'form-control form-control-sm',
                ],
            ],

            // Предприятие
            [
                'attribute' => 'company',
                'label' => 'Предприятие',
                'enableSorting' => false,
                'value' => 'acceptance.companyOwn.name',
                'headerOptions' => [
                    'style' => 'width: 180px; text-align: center;'
                ],
                'filterInputOptions' => [
                    'class' => 'form-control form-control-sm',
                ],
            ],

            // Склад
            [
                'attribute' => 'stock',
                'label' => 'Склад',
                'enableSorting' => false,
                'value' => 'acceptance.stock.name',
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
                'value' => function (Sorting $model) {
                    $assortmentName = $model->acceptance->items[0]->assortment->name;
                    $qualitySuffix = '';
                    if ($model->acceptance->items[0]->quality) {
                        $qualitySuffix = $model->acceptance->items[0]->quality->labelSuffix;
                    }

                    return $assortmentName . $qualitySuffix;
                },
                'headerOptions' => [
                    'style' => 'width: 220px; text-align: center;'
                ],
                'filterInputOptions' => [
                    'class' => 'form-control form-control-sm',
                ],
            ],

            // Качество
            [
                'format' => 'html',
                'attribute' => 'quality',
                'label' => 'Качество',
                'enableSorting' => false,
                'value' => function (Sorting $model) {
                    $quality = $model->items[0]->quality;

                    return $quality ? $quality->name : '<span style="color: red"><strong>Не назначено</strong></span>';
                },
                'headerOptions' => [
                    'style' => 'width: 100px; text-align: center;'
                ],
                'filterInputOptions' => [
                    'class' => 'form-control form-control-sm',
                ],
            ],

            // Количество
            [
                'format' => 'html',
                'attribute' => 'quantity',
                'label' => 'Кол-во',
                'enableSorting' => false,
                'value' => function (Sorting $model) {
                    $val = $model->items[0]->quantity;
                    $assortment = $model->items[0]->assortment;
                    if ($assortment->unit->is_weight) {
                        $val = number_format($val, 1, '.', ' ');
                    } else {
                        $val = number_format($val, 0, '.', ' ');
                    }
                    $unitName = ' (' . $assortment->unit->name . ')';

                    return $val > 0
                        ? $val . $unitName
                        : '<span style="color: red"><strong>' . $val . $unitName . '</strong></span>';
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
