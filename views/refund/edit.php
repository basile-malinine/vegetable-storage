<?php

use yii\data\ActiveDataProvider;
use yii\web\View;

use app\models\Documents\Order\Order;
use app\models\Documents\Refund\Refund;

/** @var View $this */
/** @var Refund $model */
/** @var ActiveDataProvider $dataProviderItem */

$this->registerJs('let controllerName = "' . Yii::$app->controller->id . '";', View::POS_HEAD);
$this->registerJs('let docId = ' . $model->id . ';', View::POS_HEAD);
$this->registerJsFile('@web/js/refund.js');

$header = 'Возврат №' . $model->id;
$docLabel = '';
switch ($model->type_id) {
    case Refund::TYPE_EXECUTOR:
        $order = Order::findOne($model->order_id);
        $docLabel = 'по Заказу ' . $order->label;
}

echo $this->render('_form', compact(['model', 'dataProviderItem', 'header', 'docLabel']));
