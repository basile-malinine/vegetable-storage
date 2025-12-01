<?php

use app\models\Workshift\Workshift;
use yii\web\View;

/* @var View $this */
/* @var Workshift $model */
/* @var string $header */

echo $this->render('_form', compact(['model', 'header']));
