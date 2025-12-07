<?php

use yii\web\View;
use app\models\DistributionCenter\DistributionCenter;

/* @var View $this */
/* @var DistributionCenter $model */

$header = 'Распределительный центр (регистрация)';

echo $this->render('_form', compact(['model', 'header']));
