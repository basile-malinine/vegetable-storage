<?php

use yii\web\View;
use app\models\Documents\Shipment\Shipment;

/* @var View $this */
/* @var Shipment $model */

$this->registerJsFile('@web/js/shipment.js');

$model->shipment_date = (new DateTime('now'))->format('d.m.Y');
$header = 'Отгрузка (новая)';

echo $this->render('_form', compact(['model', 'header']));
