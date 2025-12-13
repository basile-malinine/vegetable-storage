<?php

use yii\web\View;
use app\models\Opf\Opf;

/* @var View $this */
/* @var Opf $model */

$header = 'Организационно-правовая форма (новая)';

echo $this->render('_form', compact(['model', 'header']));
