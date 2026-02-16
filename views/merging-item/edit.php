<?php

use yii\web\View;

use app\models\Documents\Merging\MergingItem;
use app\models\Documents\Remainder\Remainder;

/** @var View $this */
/** @var MergingItem $model */

$header = 'Приёмка (изменение)';

// Записываем в сессию кол-во свободного остатка для текущего Списания
$session = Yii::$app->session;
$session->set('merging.free-qnt', [
    'quantity' => Remainder::getFreeByAcceptance($model->acceptance->id, 'quantity')
        + $model->quantity,
    'quantity_pallet' => Remainder::getFreeByAcceptance($model->acceptance->id, 'quantity_pallet')
        + $model->quantity_pallet,
    'quantity_paks' => Remainder::getFreeByAcceptance($model->acceptance->id, 'quantity_paks')
        + $model->quantity_paks,
]);

echo $this->render('_form', compact('model', 'header'));
