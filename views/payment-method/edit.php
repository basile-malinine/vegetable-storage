<?php

use yii\web\View;
use app\models\PaymentMethod\PaymentMethod;

/* @var View $this */
/* @var PaymentMethod $model */

$header = 'Менеджер [' . $model->name . ']';

echo $this->render('_form', compact(['model', 'header']));
