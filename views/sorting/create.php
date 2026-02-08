<?php

use yii\web\View;
use app\models\Documents\Sorting\Sorting;

/* @var View $this */
/* @var Sorting $model */

$actionID = Yii::$app->controller->action->id;
$this->registerJs('let actionId = "' . $actionID . '";', View::POS_HEAD);
//$this->registerJsFile('@web/js/moving.js');

$model->date = (new DateTime('now'))->format('d.m.Y');
$header = 'Переборка (новая)';

echo $this->render('_form', compact(['model', 'header']));
