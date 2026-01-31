<?php

use yii\web\View;
use app\models\Documents\Shipment\ShipmentAcceptance;

/** @var View $this */
/** @var ShipmentAcceptance $model */

$header = 'Приёмка (изменение)';


echo $this->render('_form', compact('model', 'header'));
