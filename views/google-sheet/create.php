<?php

use yii\web\View;
use app\models\GoogleSheet\GoogleSheet;

/* @var View $this */
/* @var GoogleSheet $model */

$header = 'Таблица Google (новая ссылка)';

echo $this->render('_form', compact(['model', 'header']));
