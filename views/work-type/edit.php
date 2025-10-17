<?php

use yii\web\View;
use app\models\WorkType\WorkType;


/* @var View $this */
/* @var WorkType $model */
/* @var string $header */

echo $this->render('_form', compact(['model', 'header']));
