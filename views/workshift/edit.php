<?php

use yii\web\View;
use app\models\Workshift\Workshift;

/* @var View $this */
/* @var Workshift $model */

$header = 'Смена [' . $model->name . ']';

echo $this->render('_form', compact(['model', 'header']));
