<?php

use yii\web\View;
use app\models\StickerStatus\StickerStatus;

/* @var View $this */
/* @var StickerStatus $model */

$header = 'Статус Стикер [' . $model->name . ']';

echo $this->render('_form', compact(['model', 'header']));
