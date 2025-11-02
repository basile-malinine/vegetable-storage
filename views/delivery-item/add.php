<?php

/** @var yii\web\View $this */
/** @var DeliveryItem $model */

use app\models\Documents\Delivery\DeliveryItem;

$header = 'Новая позиция';
?>

<?= $this->render('_form', compact('model', 'header')) ?>
