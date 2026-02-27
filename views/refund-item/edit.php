<?php

use yii\web\View;
use app\models\Documents\Refund\RefundItem;

/** @var View $this */
/** @var RefundItem $model */

$header = $model->assortment->name
    . ', изм: ' . $model->assortment->unit->name
    . ', вес: ' . $model->assortment->weight . ' (кг)';

// Получаем Заказ
$order = $model->refund->order;
// Записываем в сессию количество свободного для Возврата
$session = Yii::$app->session;
if ($session->has('refund.free')) {
    // Удаляем прежнее значение, если есть
    $session->remove('refund.free');
}
$shipped = (float)str_replace(' ', '', $order->shipped);
$freeQnt = .0;
$acceptance = $model->refund->acceptance;
if ($acceptance) {
    // Если по Возврату есть Приёмка, то её кол-во для валидации не учитываем
    $freeQnt = $shipped - $order->returned + $acceptance->quantity;
} else {
    $freeQnt = $shipped - $order->returned;
}
$session->set('refund.free', [
    'quantity' => $freeQnt]
);

echo $this->render('_form', compact('model', 'header'));
