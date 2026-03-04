<?php

use app\models\Documents\Increase\Increase;
use yii\web\View;
use app\models\Documents\Increase\IncreaseItem;

/** @var View $this */
/** @var IncreaseItem $model
 */

$header = $model->assortment->name
    . ', изм: ' . $model->assortment->unit->name
    . ', вес: ' . $model->assortment->weight . ' (кг)';

$docLabel = '';
switch ($model->increase->type_id) {
    case Increase::TYPE_INVENTORY:
        // Приёмка на остатке для текущего Оприходования
        $remainderAcceptance = $model->increase->sourceAcceptance;
        $docLabel = 'Приёмка ' . $remainderAcceptance->label;
        break;
    case Increase::TYPE_CORRECTION:
        // Приёмка не на остатке для текущего Оприходования
        $acceptance = $model->increase->acceptance;
        $docLabel = 'Приёмка ' . $acceptance->label;
        break;
}

echo $this->render('_form', compact('model', 'header', 'docLabel'));
