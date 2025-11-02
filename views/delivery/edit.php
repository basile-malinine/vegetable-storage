<?php

/** @var View $this */
/** @var Delivery $model */
/** @var yii\data\ActiveDataProvider $dataProviderItem */

use app\models\Documents\Delivery\Delivery;
use yii\web\View;

$header = 'Доставка №' . $model->id . ' от ' . date("d.m.Y", strtotime($model->created_at));

echo $this->render('_form', compact(['model', 'dataProviderItem', 'header']));
