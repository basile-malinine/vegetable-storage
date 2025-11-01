<?php

/** @var View $this */
/** @var Delivery $model */
/** @var yii\data\ActiveDataProvider $dataProviderItem */

use yii\web\View;
use app\models\Delivery\Delivery;

$header = 'Доставка №' . $model->id . ' от ' . date("d.m.Y", strtotime($model->created_at));

echo $this->render('_form', compact(['model', 'dataProviderItem', 'header']));
