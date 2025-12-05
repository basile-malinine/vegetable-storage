<?php

use yii\web\View;
use app\models\Assortment\AssortmentGroup;

/** @var View $this */
/** @var AssortmentGroup $model */
/** @var integer $parent_id */

if ($parent_id) {
    $header = 'Подгруппа [' . $model->name . '] для группы "' . $model->parent->name . '"';
} else {
    $header = 'Группа [' . $model->name . ']';
}

echo $this->render('_form', compact('model', 'header', 'parent_id'));
