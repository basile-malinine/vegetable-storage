<?php

use yii\web\View;
use app\models\ShipmentType\ShipmentType;


/* @var View $this */
/* @var ShipmentType $model */
/* @var string $header */

echo $this->render('_form', compact(['model', 'header']));
