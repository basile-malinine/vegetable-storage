<?php

use yii\web\View;

use app\models\Documents\Remainder\Remainder;
use app\models\Documents\Decrease\DecreaseItem;

/** @var View $this */
/** @var DecreaseItem $model
 */

$header = $model->assortment->name
    . ', изм: ' . $model->assortment->unit->name
    . ', вес: ' . $model->assortment->weight . ' (кг)';

// Приёмка на остатке для текущего Списания
$remainderAcceptance = $model->decrease->sourceAcceptance;
$docLabel = 'Приёмка ' . $remainderAcceptance->getLabel($model->quantity);

// Записываем в сессию кол-во свободного остатка для текущего Списания
$session = Yii::$app->session;
$session->set('free-qnt', [
    'quantity' => Remainder::getFreeByAcceptance($remainderAcceptance->acceptance_id, 'quantity')
        + $model->quantity,
    'quantity_pallet' => Remainder::getFreeByAcceptance($remainderAcceptance->acceptance_id, 'quantity_pallet')
        + $model->quantity_pallet,
    'quantity_paks' => Remainder::getFreeByAcceptance($remainderAcceptance->acceptance_id, 'quantity_paks')
        + $model->quantity_paks,
]);

echo $this->render('_form', compact('model', 'header', 'docLabel'));
