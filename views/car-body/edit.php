<?php

use yii\web\View;
use app\models\CarBody\CarBody;

/* @var View $this */
/* @var CarBody $model */

$header = 'Тип кузова [' . $model->name . ']';

echo $this->render('_form', compact(['model', 'header']));
