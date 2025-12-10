<?php

use yii\data\ActiveDataProvider;
use yii\web\View;

use app\models\Documents\Order\Order;

/** @var View $this */
/** @var Order $model */
/** @var ActiveDataProvider $dataProviderItem */

$this->registerJs('let controllerName = "' . Yii::$app->controller->id . '";', View::POS_HEAD);
$this->registerJs('let docId = ' . $model->id . ';', View::POS_HEAD);
$this->registerJsFile('@web/js/document.js');

$header = 'Заказ №' . $model->id . ' от ' . date("d.m.Y", strtotime($model->date));

echo $this->render('_form', compact(['model', 'dataProviderItem', 'header']));
