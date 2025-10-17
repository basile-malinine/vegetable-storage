<?php

use yii\web\View;
use app\models\Contractor\Contractor;


/* @var View $this */
/* @var Contractor $model */
/* @var string $header */

echo $this->render('_form', compact(['model', 'header']));
