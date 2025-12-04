<?php

use yii\web\View;
use app\models\ShipmentType\ShipmentType;

/* @var View $this */
/* @var ShipmentType $model */

$header = 'Тип отгрузки (новый)';

echo $this->render('_form', compact(['model', 'header']));
