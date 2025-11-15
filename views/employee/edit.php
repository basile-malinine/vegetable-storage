<?php

use yii\web\View;
use app\models\Employee\Employee;

/* @var View $this */
/* @var Employee $model */

$header = 'Сотрудник [' . $model->full_name . ']';

echo $this->render('_form', compact(['model', 'header']));
