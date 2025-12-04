<?php

use yii\web\View;
use app\models\Product\Product;

/* @var View $this */
/* @var Product $model */

$header = 'Продукт [' . $model->name . ']';

echo $this->render('_form', compact(['model', 'header']));
