<?php

use yii\data\ActiveDataProvider;
use yii\web\View;
use app\models\Documents\Delivery\Delivery;

/** @var View $this */
/** @var Delivery $model */
/** @var ActiveDataProvider $dataProviderItem */

$header = 'Доставка №' . $model->id . ' от ' . date("d.m.Y", strtotime($model->created_at));

echo $this->render('_form', compact(['model', 'dataProviderItem', 'header']));
