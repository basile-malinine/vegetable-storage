<?php

use yii\web\View;
use app\models\LocationStatus\LocationStatus;

/* @var View $this */
/* @var LocationStatus $model */

$header = 'Статус Местоположение [' . $model->name . ']';

echo $this->render('_form', compact(['model', 'header']));
