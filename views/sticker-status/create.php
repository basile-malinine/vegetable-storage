<?php

use yii\web\View;
use app\models\StickerStatus\StickerStatus;

/* @var View $this */
/* @var StickerStatus $model */
$header = 'Статус Стикер (новый)';

echo $this->render('_form', compact(['model', 'header']));
