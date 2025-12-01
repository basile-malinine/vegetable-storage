<?php

use app\models\PalletType\PalletType;
use yii\web\View;

/* @var View $this */
/* @var PalletType $model */
/* @var string $header */

echo $this->render('_form', compact(['model', 'header']));
