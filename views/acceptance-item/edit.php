<?php

use yii\web\View;

use app\models\Documents\Acceptance\Acceptance;
use app\models\Documents\Acceptance\AcceptanceItem;

/** @var View $this */
/** @var AcceptanceItem $model */

$header = $model->assortment->name
    . ', изм: ' . $model->assortment->unit->name
    . ', вес: ' . $model->assortment->weight . ' (кг)';

$typeId = $model->acceptance->type_id;
if ($typeId === Acceptance::TYPE_REFUND) {
    // Получаем Заказ
    $order = $model->acceptance->parentDoc->order;
    // Записываем в сессию количество свободного для Возврата
    $session = Yii::$app->session;
    if ($session->has('refund.free')) {
        $session->remove('refund.free');
    }
    $shipped = (float)str_replace(' ', '', $order->shipped);
    $session->set('refund.free', [
        'quantity' => $shipped - $order->returned + $model->quantity,
    ]);
}

echo $this->render('_form', compact('model', 'header'));
