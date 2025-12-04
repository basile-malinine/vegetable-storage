<?php

use yii\web\View;
use app\models\Unit\Unit;

/* @var View $this */
/* @var Unit $model */

$header = 'Единица измерения [' . $model->name . ']';

echo $this->render('_form', compact(['model', 'header']));
