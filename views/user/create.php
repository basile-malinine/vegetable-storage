<?php

use yii\web\View;
use app\models\User\User;

/* @var View $this */
/* @var User $model */
/* @var string $header */

echo $this->render('_form', compact(['model', 'header']));
