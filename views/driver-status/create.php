<?php

use app\models\DriverStatus\DriverStatus;
use yii\web\View;

/* @var View $this */
/* @var DriverStatus $model */
/* @var string $header */

echo $this->render('_form', compact(['model', 'header']));
