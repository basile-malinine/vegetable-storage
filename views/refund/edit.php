<?php

use yii\data\ActiveDataProvider;
use yii\web\View;

use app\models\Documents\Refund\Refund;

/** @var View $this */
/** @var Refund $model */
/** @var ActiveDataProvider $dataProviderItem */

$this->registerJs('let controllerName = "' . Yii::$app->controller->id . '";', View::POS_HEAD);
$this->registerJs('let docId = ' . $model->id . ';', View::POS_HEAD);
$this->registerJsFile('@web/js/refund.js');

$header = 'Возврат №' . $model->id;
$docLabel = '';
$order = $model->order;
$docLabel = 'по Заказу ' . $order->label;

if (Yii::$app->session->has('refund.free')) {
    // Удаляем прежнее значение свободного количества, если есть
    Yii::$app->session->remove('refund.free');
}

echo $this->render('_form', compact(['model', 'dataProviderItem', 'header', 'docLabel']));
