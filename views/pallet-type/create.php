<?php

use yii\web\View;
use app\models\PalletType\PalletType;

/* @var View $this */
/* @var PalletType $model */

$header = 'Тип паллета (новый)';

echo $this->render('_form', compact(['model', 'header']));
