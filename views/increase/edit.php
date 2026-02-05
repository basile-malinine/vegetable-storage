<?php

use yii\data\ActiveDataProvider;
use yii\web\View;

use app\models\Documents\Increase\Increase;

/** @var View $this */
/** @var Increase $model */
/** @var ActiveDataProvider $dataProviderItem */

$actionID = Yii::$app->controller->action->id;
$this->registerJs('let controllerName = "' . Yii::$app->controller->id . '";', View::POS_HEAD);
$this->registerJs('let docId = ' . $model->id . ';', View::POS_HEAD);
$this->registerJs('let actionId = "' . $actionID . '";', View::POS_HEAD);
$this->registerJsFile('@web/js/increase.js');

$header = 'Оприходование №' . $model->id;

echo $this->render('_form', compact(['model', 'dataProviderItem', 'header']));
