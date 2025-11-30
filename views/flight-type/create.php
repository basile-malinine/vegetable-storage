<?php

use app\models\FlightType\FlightType;
use yii\web\View;

/* @var View $this */
/* @var FlightType $model */
/* @var string $header */

echo $this->render('_form', compact(['model', 'header']));
