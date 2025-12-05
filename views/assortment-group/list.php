<?php

use yii\grid\GridView;
use app\models\Assortment\AssortmentGroup;
use app\models\Assortment\AssortmentGroupSearch;

/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var yii\web\View $this */
/** @var AssortmentGroupSearch $searchModel */
/** @var integer $parent_id */

$header = 'Классификатор номенклатуры';
if ($parent_id) {
    $header .= ' для группы "' . AssortmentGroup::findOne($parent_id)->name . '"';
}

$this->registerJs('let controllerName = "assortment-group";', \yii\web\View::POS_HEAD);
$this->registerJsFile('@web/js/contextmenu-list.js');

?>
<div class="page-content">
    <div class="page-top-panel">
        <div class="page-top-panel-header d-flex">
            <?php
            if ($parent_id) {
                echo '<a href="/assortment-group/edit/' . $parent_id . '"'
                    . ' class="btn btn-return btn-light btn-outline-secondary btn-sm mt-1 me-3 pe-2"><i class="fa fa-arrow-left"></i>'
                    . '</a>';
            }
            ?>
            <?= $header ?>
            <a href="/assortment-group/create/<?= $parent_id ?: '' ?>"
               class="btn btn-light btn-outline-secondary btn-sm mt-1 ms-auto pe-3">
                <i class="fa fa-plus"></i>
                <span class="ms-2">Добавить</span>
            </a>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,

        'rowOptions' => function ($model, $key, $index, $grid) {
            $parent_id = Yii::$app->request->get('parent_id');
            return [
                'class' => 'contextMenuRow',
                'data-row-id' => $model->id . ($parent_id ? '/' . $parent_id : ''),
            ];
        },

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

            // Группа
            [
                'format' => 'raw',
                'attribute' => 'name',
                'label' => $parent_id ? 'Подгруппа' : 'Группа',
                'enableSorting' => false,
                'headerOptions' => [
                    'style' => 'width: 420px;'
                ],
            ],

            // Подгруппы
            [
                'format' => 'raw',
                'attribute' => 'child',
                'visible' => !$parent_id,
                'value' => function ($model, $key, $index, $value) {
                    $res = [];
                    foreach ($model->child as $child) {
                        $res[] = $child->name;
                    }
                    sort($res);
                    return implode(', ', $res);
                },
                'headerOptions' => [
                    'style' => 'width: 600px;'
                ],
            ],

            // Пустота
            [
            ],
        ],
    ]); ?>

</div>
