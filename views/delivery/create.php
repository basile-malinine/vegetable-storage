<?php

use yii\web\View;
use app\models\Documents\Delivery\Delivery;

/* @var View $this */
/* @var Delivery $model */

$model->shipment_date = (new DateTime('now'))->format('d.m.Y');
$model->unloading_date = (new DateTime('now'))->format('d.m.Y');

$header = 'Поставка (новая)';

echo $this->render('_form', compact(['model', 'header']));
