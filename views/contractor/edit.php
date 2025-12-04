<?php

use yii\web\View;
use app\models\Contractor\Contractor;


/* @var View $this */
/* @var Contractor $model */

$header = 'Исполнитель [' . $model->name . ']';

echo $this->render('_form', compact(['model', 'header']));
