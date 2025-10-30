<?php

use yii\bootstrap5\Tabs;
use yii\grid\GridView;
use app\models\LegalSubject\LegalSubjectSearch;

/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var yii\web\View $this */
/** @var LegalSubjectSearch $searchModel */
/** @var string $header */

$actionId = yii::$app->controller->action->id;
if ($actionId === 'index') $actionId = 'all';

$this->registerJs('let controllerName = "legal-subject";', \yii\web\View::POS_HEAD);
$this->registerJsFile('@web/js/contextmenu-list.js');
?>
<div class="page-content">
    <div class="page-top-panel">
        <div class="page-top-panel-header d-flex">
            <?= $header ?>
            <?php if (Yii::$app->user->can('legal_subject.create')): ?>
            <a href="/legal-subject/create" class="btn btn-light btn-outline-secondary btn-sm mt-1 ms-auto pe-3">
                <i class="fa fa-plus"></i>
                <span class="ms-2">Добавить</span>
            </a>
            <?php endif; ?>
        </div>
    </div>

    <?= Tabs::Widget([
        'options' => [
            'class' => 'mt-1 mb-2',
        ],
        'items' => [
            [
                'label' => 'Все',
                'url' => '/legal-subject/all',
                'active' => $actionId === 'all',
            ],

            [
                'label' => 'Поставщики',
                'url' => '/legal-subject/supplier',
                'active' => $actionId === 'supplier',
            ],

            [
                'label' => 'Покупатели',
                'url' => '/legal-subject/buyer',
                'active' => $actionId === 'buyer',
            ],
        ]
    ]); ?>

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
            // Юридическое / физическое (иконка)
            [
                'format' => 'raw',
                'value' => function ($model) {
                    return !$model->is_legal ? '<i class="fas fa-user"></i>' : '';
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
                ]
            ],

            // Название или ФИО
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

            // ИНН
            [
                'attribute' => 'inn',
                'label' => 'Идентификатор',
                'enableSorting' => false,
                'headerOptions' => [
                    'style' => 'width: 180px;'
                ],
                'filterInputOptions' => [
                    'class' => 'form-control form-control-sm',
                ],
            ],

            // Поставщик
            [
                'attribute' => 'is_supplier',
                'label' => 'Пост.',
                'enableSorting' => false,
                'value' => function ($model) {
                    return $model->is_supplier ? 'Да' : '';
                },
                'headerOptions' => [
                    'style' => 'width: 80px;'
                ],
                'filterInputOptions' => [
                    'class' => 'form-control form-control-sm',
                ],
                'contentOptions' => [
                    'style' => 'text-align: center;',
                ]
            ],

            // Покупатель
            [
                'attribute' => 'is_buyer',
                'label' => 'Покуп.',
                'enableSorting' => false,
                'value' => function ($model) {
                    return $model->is_buyer ? 'Да' : '';
                },
                'headerOptions' => [
                    'style' => 'width: 80px;'
                ],
                'filterInputOptions' => [
                    'class' => 'form-control form-control-sm',
                ],
                'contentOptions' => [
                    'style' => 'text-align: center;',
                ]
            ],

            // Страна
            [
                'attribute' => 'country',
                'enableSorting' => false,
                'value' => 'country.name',
                'headerOptions' => [
                    'style' => 'width: 240px;'
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
            ],
        ],
    ]); ?>

</div>
