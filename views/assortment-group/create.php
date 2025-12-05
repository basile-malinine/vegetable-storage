<?php

use yii\web\View;
use app\models\Assortment\AssortmentGroup;

/** @var View $this */
/** @var AssortmentGroup $model */
/** @var integer $parent_id */

if ($parent_id) {
//    $model->parent_id = $parent_id;
    $header = 'Подгруппа (новая)' . ' для группы "' . $model->parent->name . '"';
} else {
    $header = 'Группа (новая)';
}

echo $this->render('_form', compact(['model', 'header', 'parent_id']));
