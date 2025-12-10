<?php

use yii\web\View;
use app\models\Documents\Order\Order;

/* @var View $this */
/* @var Order $model */

$header = 'Заказ (новый)';

echo $this->render('_form', compact(['model', 'header']));
