<?php

use yii\web\View;
use app\models\OrderStatus\OrderStatus;

/* @var View $this */
/* @var OrderStatus $model */
$header = 'Статус Заказ [' . $model->name . ']';

echo $this->render('_form', compact(['model', 'header']));
