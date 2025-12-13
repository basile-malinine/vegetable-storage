<?php

use app\models\PaymentMethod\PaymentMethod;
use yii\web\View;

/* @var View $this */
/* @var PaymentMethod $model */

$header = 'Способ оплаты (новый)';

echo $this->render('_form', compact(['model', 'header']));
