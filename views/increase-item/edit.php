<?php

use yii\web\View;
use app\models\Documents\Increase\IncreaseItem;

/** @var View $this */
/** @var IncreaseItem $model
 */

$header = $model->assortment->name
    . ', изм: ' . $model->assortment->unit->name
    . ', вес: ' . $model->assortment->weight . ' (кг)';

// Приёмка на остатке для текущего Оприходования
$remainderAcceptance = $model->increase->sourceAcceptance;
$docLabel = 'Приёмка ' . $remainderAcceptance->label;

echo $this->render('_form', compact('model', 'header', 'docLabel'));
