<?php

use yii\web\View;
use app\models\Documents\Moving\Moving;

/* @var View $this */
/* @var Moving $model */

$actionID = Yii::$app->controller->action->id;
$this->registerJs('let actionId = "' . $actionID . '";', View::POS_HEAD);
$this->registerJsFile('@web/js/moving.js');

$model->date = (new DateTime('now'))->format('d.m.Y');
$header = 'Перемещение (новое)';

echo $this->render('_form', compact(['model', 'header']));
