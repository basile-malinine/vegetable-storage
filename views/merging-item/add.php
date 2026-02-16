<?php

/** @var yii\web\View $this */

/** @var MergingItem $model */

use app\models\Documents\Merging\MergingItem;

$header = 'Объединение (новое)';

$session = Yii::$app->session;
if ($session->has('merging.free-qnt')) {
    $ss = $session->remove('merging.free-qnt');
}

echo $this->render('_form', compact('model', 'header'));
