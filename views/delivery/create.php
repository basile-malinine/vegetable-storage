<?php

use app\models\Documents\Delivery\Delivery;
use yii\web\View;

/* @var View $this */
/* @var Delivery $model */

$header = 'Доставка (новая)';

echo $this->render('_form', compact(['model', 'header']));
