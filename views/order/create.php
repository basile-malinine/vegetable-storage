<?php

use yii\web\View;
use app\models\Documents\Order\Order;

/* @var View $this */
/* @var Order $model */

$model->date = (new DateTime('now'))->format('d.m.Y');

$header = 'Заказ (новый)';

echo $this->render('_form', compact(['model', 'header']));
