<?php

use yii\web\View;
use app\models\FlightType\FlightType;

/* @var View $this */
/* @var FlightType $model */

$header = 'Тип рейса [' . $model->name . ']';

echo $this->render('_form', compact(['model', 'header']));
