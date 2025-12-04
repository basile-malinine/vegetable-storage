<?php

use yii\web\View;
use app\models\WorkType\WorkType;

/* @var View $this */
/* @var WorkType $model */

$header = 'Вид работы (новый)';

echo $this->render('_form', compact(['model', 'header']));
