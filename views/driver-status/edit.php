<?php

use yii\web\View;
use app\models\DriverStatus\DriverStatus;

/* @var View $this */
/* @var DriverStatus $model */

$header = 'Статус Водитель [' . $model->name . ']';

echo $this->render('_form', compact(['model', 'header']));
