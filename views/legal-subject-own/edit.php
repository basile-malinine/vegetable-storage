<?php

use yii\web\View;
use app\models\LegalSubject\LegalSubject;

/** @var View $this */
/** @var LegalSubject $model */

$header = 'Собственное предприятие [' . $model->name . ']';

echo $this->render('_form', compact('model', 'header'));
