<?php

/** @var yii\web\View $this */
/** @var \app\models\Documents\Delivery\DeliveryItem $model */

use app\models\Documents\Delivery\DeliveryItem;

$header = 'Позиция [' . $model->assortment->name . ']';

$this->render('_form', compact('model', 'header'));
