<?php

use yii\web\View;
use app\models\Documents\Increase\Increase;

/* @var View $this */
/* @var Increase $model */

$actionID = Yii::$app->controller->action->id;
$this->registerJs('let actionId = "' . $actionID . '";', View::POS_HEAD);
$this->registerJsFile('@web/js/increase.js');

$model->date = (new DateTime('now'))->format('d.m.Y');
$header = 'Оприходование (новое)';

echo $this->render('_form', compact(['model', 'header']));
