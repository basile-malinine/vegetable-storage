<?php

use yii\web\View;
use app\models\AcceptanceType\AcceptanceType;


/* @var View $this */
/* @var AcceptanceType $model */

$header = 'Тип приемки [' . $model->name . ']';

echo $this->render('_form', compact(['model', 'header']));
