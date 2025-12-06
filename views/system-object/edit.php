<?php

use yii\web\View;
use app\models\SystemObject\SystemObject;

/* @var View $this */
/* @var SystemObject $model */

$header = 'Объект системы [' . $model->name . ']';

echo $this->render('_form', compact(['model', 'header']));
