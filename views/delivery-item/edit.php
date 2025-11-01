<?php

/** @var yii\web\View $this */
/** @var DeliveryItem $model */

use app\models\Delivery\DeliveryItem;

$header = 'Позиция [' . $model->assortment->name . ']';

?>

<?= $this->render('_form', compact('model', 'header')) ?>
