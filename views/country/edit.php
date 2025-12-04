<?php

use yii\web\View;
use app\models\Country\Country;

/** @var View $this */
/** @var Country $model */

$header = 'Страна [' . $model->name . ']';

echo $this->render('_form', compact('model', 'header'));
