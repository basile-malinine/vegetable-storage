<?php

use yii\web\View;
use app\models\User\User;

/* @var View $this */
/* @var User $model */

$header = 'Пользователь (новый)';

echo $this->render('_form', compact(['model', 'header']));
