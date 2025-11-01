<?php

use yii\web\View;
use app\models\Delivery\Delivery;

/* @var View $this */
/* @var Delivery $model */

$header = 'Доставка (новая)';

echo $this->render('_form', compact(['model', 'header']));
