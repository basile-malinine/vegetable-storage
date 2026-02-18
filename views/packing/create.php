<?php

use yii\web\View;
use app\models\Documents\Packing\Packing;

/* @var View $this */
/* @var Packing $model */

$actionID = Yii::$app->controller->action->id;
$this->registerJs('let actionId = "' . $actionID . '";', View::POS_HEAD);
$this->registerJsFile('@web/js/packing.js');

$model->date = (new DateTime('now'))->format('d.m.Y');
$header = 'Фасовка (новая)';

echo $this->render('_form', compact(['model', 'header']));
