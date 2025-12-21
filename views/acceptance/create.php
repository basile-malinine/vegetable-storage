<?php

use yii\web\View;
use app\models\Documents\Acceptance\Acceptance;

/* @var View $this */
/* @var Acceptance $model */

$this->registerJsFile('@web/js/acceptance.js');

$model->acceptance_date = (new DateTime('now'))->format('d.m.Y');
$header = 'Приёмка (новая)';

echo $this->render('_form', compact(['model', 'header']));
