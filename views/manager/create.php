<?php

use yii\web\View;
use app\models\Manager\Manager;

/* @var View $this */
/* @var Manager $model */

$header = 'Менеджер (новый)';

echo $this->render('_form', compact(['model', 'header']));
