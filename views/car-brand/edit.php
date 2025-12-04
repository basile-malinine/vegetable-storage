<?php

use yii\web\View;
use app\models\CarBrand\CarBrand;

/* @var View $this */
/* @var CarBrand $model */

$header = 'Марка автомобиля [' . $model->name . ']';

echo $this->render('_form', compact(['model', 'header']));
