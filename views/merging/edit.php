<?php

use yii\data\ActiveDataProvider;
use yii\web\View;

use app\models\Documents\Merging\Merging;

/** @var View $this */
/** @var Merging $model */
/** @var ActiveDataProvider $dataProviderItem */

$actionID = Yii::$app->controller->action->id;
$this->registerJs('let controllerName = "' . Yii::$app->controller->id . '";', View::POS_HEAD);
$this->registerJs('let docId = ' . $model->id . ';', View::POS_HEAD);
$this->registerJs('let actionId = "' . $actionID . '";', View::POS_HEAD);
$this->registerJsFile('@web/js/merging.js');

$header = 'Объединение №' . $model->id;

echo $this->render('_form', compact(['model', 'dataProviderItem', 'header']));
