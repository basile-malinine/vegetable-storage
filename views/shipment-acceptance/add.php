<?php

/** @var yii\web\View $this */
/** @var ShipmentAcceptance $model */

use app\models\Documents\Shipment\ShipmentAcceptance;

$header = 'Приёмка (новая)';


echo $this->render('_form', compact('model', 'header'));
