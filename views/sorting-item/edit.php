<?php

use yii\web\View;
use app\models\Documents\Sorting\SortingItem;

/** @var View $this */
/** @var SortingItem $model
 */

$header = $model->assortment->name
    . ', изм: ' . $model->assortment->unit->name
    . ', вес: ' . $model->assortment->weight . ' (кг)';

// Приёмка на остатке для текущего Оприходования
$remainderAcceptance = $model->sorting->sourceAcceptance;
$docLabel = 'Приёмка ' . $remainderAcceptance->label;

echo $this->render('_form', compact('model', 'header', 'docLabel'));
