<?php

/** @var View $this */
/** @var Delivery $model */

/** @var ActiveDataProvider $dataProvider */

use yii\bootstrap5\Html;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\web\View;

use app\models\Documents\Delivery\Delivery;
use app\models\Documents\Order\Order;

$header = 'Заказы для Поставки №' . $model->id;

?>
<div class="page-content">
    <div class="page-top-panel">
        <div class="page-top-panel-header d-flex">
            <?= $header ?>
        </div>
    </div>

    <div class="row form-last-row">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                // Check
                [
                    'class' => 'yii\grid\CheckboxColumn',
                    'checkboxOptions' =>
                        function (Order $model) {
                            $val = (bool)$model->delivery_id;
                            return [
                                'checked' => $val,
                                'onchange' => '
                                if (this.checked) {
                                    $.post(
                                        "/order/set-link-to-delivery",
                                        {
                                            delivery_id: ' . $_GET['id'] . ',
                                            val_delivery_id: ' . $_GET['id'] . ',
                                            id: ' . $model->id . '
                                        }, 
                                        (data) => {
                                        }
                                    );
                                } else {
                                    $.post(
                                        "/order/set-link-to-delivery",
                                        {
                                            delivery_id: ' . $_GET['id'] . ',
                                            val_delivery_id: null,
                                            id: ' . $model->id . '
                                        }, 
                                        (data) => {
                                        }
                                    );
                                }
                            '
                            ];
                        },
                    'contentOptions' => [
                        'style' => 'text-align: center;'
                    ],
                    'headerOptions' => [
                        'style' => 'width: 30px;'
                    ],
                ],

                // Тип Через исполнителя (иконка)
                [
                    'format' => 'raw',
                    'value' => function (Order $model) {
                        return $model->type_id === Order::TYPE_EXECUTOR
                            ? '<i class="fas fa-arrows-h"></i>' : '';
                    },
                    'contentOptions' => [
                        'style' => 'color: #0077ff; text-align: center',
                    ],
                    'headerOptions' => [
                        'style' => 'width: 30px',
                    ],
                ],

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
                    'label' => 'Дата',
                    'enableSorting' => false,
                    'value' => function ($model) {
                        return date("d.m.Y", strtotime($model->created_at));
                    },
                    'headerOptions' => [
                        'style' => 'width: 80px; text-align: center;'
                    ],
                    'contentOptions' => [
                        'style' => 'text-align: center;'
                    ],
                    'filterInputOptions' => [
                        'class' => 'form-control form-control-sm',
                    ],
                ],

                // Сеть
                [
                    'attribute' => 'buyer_id',
                    'enableSorting' => false,
                    'value' => 'buyer.name',
                    'headerOptions' => [
                        'style' => 'width: 200px;'
                    ],
                ],

                // Распределительный центр
                [
                    'format' => 'raw',
                    'label' => 'РЦ',
                    'attribute' => 'distribution_center_id',
                    'enableSorting' => false,
                    'value' => 'distributionCenter.name',
                    'headerOptions' => [
                        'style' => 'width: 40px;'
                    ],
                ],

                // Номенклатура
                [
                    'format' => 'raw',
                    'attribute' => 'assortment',
                    'label' => 'Номенклатура',
                    'enableSorting' => false,
                    'value' => function (Order $model) {
                        return (bool)$model->items ? $model->items[0]->label : 'Нет состава';
                    },
                    'headerOptions' => [
                        'style' => 'width: 180px;'
                    ],
                ],
            ],
        ]); ?>
    </div>

    <div class="form-group">
        <?= Html::a('Закрыть', '/delivery/edit/' . $model->id, ['class' => 'btn btn-light btn-outline-secondary btn-sm']) ?>
    </div>
</div>

