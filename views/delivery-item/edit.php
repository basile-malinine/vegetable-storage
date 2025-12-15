<?php

use yii\web\View;
use app\models\Documents\Delivery\DeliveryItem;

/** @var View $this */
/** @var DeliveryItem $model */

$header = 'Позиция [' . $model->assortment->name . ']';

echo $this->render('_form', compact('model', 'header'));
