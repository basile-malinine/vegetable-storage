<?php

use yii\web\View;
use app\models\Assortment\Assortment;

/* @var View $this */
/* @var Assortment $model */
/* @var string $header */

echo $this->render('_form', compact(['model', 'header']));
