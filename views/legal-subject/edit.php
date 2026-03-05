<?php

use yii\web\View;
use app\models\LegalSubject\LegalSubject;

/** @var View $this */
/** @var LegalSubject $model */

$header = '';
switch ($model->type_id) {
    case LegalSubject::TYPE_COMPANY:
        $header = 'Юридическое лицо';
        break;
    case LegalSubject::TYPE_BUSINESSMAN:
    case LegalSubject::TYPE_PERSON:
        $header = 'Физическое лицо';
        break;
}
$header .= ' [' . $model->name . ']';


echo $this->render('_form', compact('model', 'header'));
