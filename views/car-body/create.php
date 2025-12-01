<?php

use app\models\CarBody\CarBody;
use yii\web\View;

/* @var View $this */
/* @var CarBody $model */
/* @var string $header */

echo $this->render('_form', compact(['model', 'header']));
