<?php

use yii\web\View;
use app\models\Assortment\Assortment;

/* @var View $this */
/* @var Assortment $model */

$header = 'Номенклатура [' . $model->name . ']';

echo $this->render('_form', compact(['model', 'header']));
