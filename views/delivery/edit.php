<?php

use yii\data\ActiveDataProvider;
use yii\web\View;

use app\models\Documents\Delivery\Delivery;

/** @var View $this */
/** @var Delivery $model */
/** @var ActiveDataProvider $dataProviderItem */

$this->registerJs('let controllerName = "' . Yii::$app->controller->id . '";', View::POS_HEAD);
$this->registerJs('let docId = ' . $model->id . ';', View::POS_HEAD);
$this->registerJsFile('@web/js/document.js');

$header = 'Поставка №' . $model->id . ' от ' . date("d.m.Y", strtotime($model->created_at));

echo $this->render('_form', compact(['model', 'dataProviderItem', 'header']));
