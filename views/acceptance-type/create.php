<?php

use yii\web\View;
use app\models\AcceptanceType\AcceptanceType;


/* @var View $this */
/* @var AcceptanceType $model */
/* @var string $header */

echo $this->render('_form', compact(['model', 'header']));
