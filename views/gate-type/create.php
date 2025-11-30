<?php

use app\models\GateType\GateType;
use yii\web\View;

/* @var View $this */
/* @var GateType $model */
/* @var string $header */

echo $this->render('_form', compact(['model', 'header']));
