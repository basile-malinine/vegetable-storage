<?php

use yii\web\View;
use app\models\GateType\GateType;

/* @var View $this */
/* @var GateType $model */

$header = 'Ворота / Рампы (новые)';

echo $this->render('_form', compact(['model', 'header']));
