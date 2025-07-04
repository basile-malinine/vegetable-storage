<?php

use yii\web\View;
use app\models\Unit\Unit;

/* @var View $this */
/* @var Unit $model */
/* @var string $header */

echo $this->render('_form', compact(['model', 'header']));
