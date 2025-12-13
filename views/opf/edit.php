<?php

use yii\web\View;
use app\models\Opf\Opf;

/* @var View $this */
/* @var Opf $model */

$header = 'Организационно-правовая форма [' . $model->name . ']';

echo $this->render('_form', compact(['model', 'header']));
