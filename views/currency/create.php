<?php

use yii\web\View;
use app\models\Currency\Currency;

/* @var View $this */
/* @var Currency $model */

$header = 'Валюта (новая)';

echo $this->render('_form', compact(['model', 'header']));
