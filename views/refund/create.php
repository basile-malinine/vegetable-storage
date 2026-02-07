<?php

use yii\web\View;
use app\models\Documents\Refund\Refund;

/* @var View $this */
/* @var Refund $model */

$this->registerJsFile('@web/js/refund.js');

$model->date = (new DateTime('now'))->format('d.m.Y');
$header = 'Возврат (новый)';

echo $this->render('_form', compact(['model', 'header']));
