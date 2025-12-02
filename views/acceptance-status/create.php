<?php

use yii\web\View;
use app\models\AcceptanceStatus\AcceptanceStatus;

/* @var View $this */
/* @var AcceptanceStatus $model */
$header = 'Статус Приёмка (новый)';

echo $this->render('_form', compact(['model', 'header']));
