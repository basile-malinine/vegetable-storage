<?php

use yii\web\View;
use app\models\Quality\Quality;

/* @var View $this */
/* @var Quality $model */

$header = 'Тип паллета (новый)';

echo $this->render('_form', compact(['model', 'header']));
