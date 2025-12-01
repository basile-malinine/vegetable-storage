<?php

use app\models\CarBrand\CarBrand;
use yii\web\View;

/* @var View $this */
/* @var CarBrand $model */
/* @var string $header */

echo $this->render('_form', compact(['model', 'header']));
