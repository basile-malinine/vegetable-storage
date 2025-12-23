<?php

use yii\web\View;
use app\models\Documents\Refund\RefundItem;

/** @var View $this */
/** @var RefundItem $model */

$header = $model->assortment->name
    . ', изм: ' . $model->assortment->unit->name
    . ', вес: ' . $model->assortment->weight . ' (кг)';

echo $this->render('_form', compact('model', 'header'));
