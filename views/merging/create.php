<?php

use yii\web\View;
use app\models\Documents\Merging\Merging;

/* @var View $this */
/* @var Merging $model */

$actionID = Yii::$app->controller->action->id;
$this->registerJs('let actionId = "' . $actionID . '";', View::POS_HEAD);
$this->registerJsFile('@web/js/merging.js');

$model->date = (new DateTime('now'))->format('d.m.Y');
$header = 'Объединение (новое)';

echo $this->render('_form', compact(['model', 'header']));
