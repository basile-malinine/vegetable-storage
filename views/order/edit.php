<?php

use yii\data\ActiveDataProvider;
use yii\web\View;

use app\models\Documents\Order\Order;

/** @var View $this */
/** @var Order $model */
/** @var ActiveDataProvider $dataProviderItem */

$header = 'Заказ №' . $model->id . ' от ' . date("d.m.Y", strtotime($model->created_at));

echo $this->render('_form', compact(['model', 'dataProviderItem', 'header']));
