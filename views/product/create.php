<?php

use yii\web\View;
use app\models\Product\Product;

/* @var View $this */
/* @var Product $model */
/* @var string $header */

echo $this->render('_form', compact(['model', 'header']));
