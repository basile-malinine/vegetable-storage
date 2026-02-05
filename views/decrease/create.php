<?php

use yii\web\View;
use app\models\Documents\Decrease\Decrease;

/* @var View $this */
/* @var Decrease $model */

$actionID = Yii::$app->controller->action->id;
$this->registerJs('let actionId = "' . $actionID . '";', View::POS_HEAD);
$this->registerJsFile('@web/js/decrease.js');

$model->date = (new DateTime('now'))->format('d.m.Y');
$header = 'Списание (новое)';

echo $this->render('_form', compact(['model', 'header']));
