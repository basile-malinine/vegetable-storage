<?php

use yii\data\ActiveDataProvider;
use yii\web\View;

use app\models\Documents\Acceptance\Acceptance;
use app\models\Documents\Delivery\Delivery;
use app\models\Documents\Refund\Refund;

/** @var View $this */
/** @var Acceptance $model */
/** @var ActiveDataProvider $dataProviderItem */

$this->registerJs('let controllerName = "' . Yii::$app->controller->id . '";', View::POS_HEAD);
$this->registerJs('let docId = ' . $model->id . ';', View::POS_HEAD);
$this->registerJsFile('@web/js/acceptance.js');

$header = 'Приёмка №' . $model->id;

switch ($model->type_id) {
    case Acceptance::TYPE_DELIVERY:
        $delivery = Delivery::findOne($model->parent_doc_id);
        $header .= ' по Поставке ' . $delivery->label;
        break;
    case Acceptance::TYPE_REFUND:
        $delivery = Refund::findOne($model->parent_doc_id);
        $header .= ' по Возврату ' . $delivery->label;
        break;
}

echo $this->render('_form', compact(['model', 'dataProviderItem', 'header']));
