<?php

use yii\web\View;
use app\models\Stock\Stock;

/* @var View $this */
/* @var Stock $model */

$header = 'Склады [' . $model->name . ']';

echo $this->render('_form', compact(['model', 'header']));
