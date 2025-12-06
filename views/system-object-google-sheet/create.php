<?php

use yii\web\View;
use app\models\SystemObjectGoogleSheet\SystemObjectGoogleSheet;

/* @var View $this */
/* @var SystemObjectGoogleSheet $model */

$header = 'Связь объекта с Google (новая)';

echo $this->render('_form', compact(['model', 'header']));
