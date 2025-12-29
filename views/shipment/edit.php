<?php

use yii\data\ActiveDataProvider;
use yii\web\View;

use app\models\Documents\Moving\Moving;
use app\models\Documents\Order\Order;
use app\models\Documents\Shipment\Shipment;

/** @var View $this */
/** @var Shipment $model */
/** @var ActiveDataProvider $dataProviderAcceptance */

$this->registerJs('let controllerName = "' . Yii::$app->controller->id . '";', View::POS_HEAD);
$this->registerJs('let docId = ' . $model->id . ';', View::POS_HEAD);
$this->registerJsFile('@web/js/shipment.js');

$header = 'Отгрузка №' . $model->id;
$docLabel = '';
switch ($model->type_id) {
    case Shipment::TYPE_ORDER:
        $order = Order::findOne($model->parent_doc_id);
        $docLabel = 'по Заказу ' . $order->label;
        break;
    case Shipment::TYPE_MOVING:
        $moving = Moving::findOne($model->parent_doc_id);
        $docLabel = 'по Перемещению ' . $moving->label;
        break;
}

echo $this->render('_form', compact(['model', 'dataProviderAcceptance', 'header', 'docLabel']));
