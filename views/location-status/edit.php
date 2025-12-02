<?php

use app\models\LocationStatus\LocationStatus;
use yii\web\View;

/* @var View $this */
/* @var LocationStatus $model */
$header = 'Статус Местоположение [' . $model->name . ']';

echo $this->render('_form', compact(['model', 'header']));
