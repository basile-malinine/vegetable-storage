<?php

use yii\web\View;
use app\models\Workshift\Workshift;

/* @var View $this */
/* @var Workshift $model */

$header = 'Смена (новая)';

echo $this->render('_form', compact(['model', 'header']));
