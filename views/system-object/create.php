<?php

use yii\web\View;
use app\models\SystemObject\SystemObject;

/* @var View $this */
/* @var SystemObject $model */

$header = 'Объект системы (регистрация)';

echo $this->render('_form', compact(['model', 'header']));
