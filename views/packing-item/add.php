<?php

/** @var yii\web\View $this */

/** @var PackingItem $model */

use app\models\Documents\Packing\PackingItem;

$header = 'Приёмка (новая)';

$session = Yii::$app->session;
if ($session->has('packing.free-qnt')) {
    $session->remove('packing.free-qnt');
}

echo $this->render('_form', compact('model', 'header'));
