<?php
yii\bootstrap5\Modal::begin([
    'headerOptions' => [
        'id' => 'modalHeader',
        'hidden' => true
    ],
    'size' => 'modal-lg',
    'options' => [
        'id' => 'modal',
        'tabindex' => false,
    ],
    'clientOptions' => [
        'backdrop' => 'static',
        'keyboard' => true,
    ],
]);
echo "<div id='modalContent'></div>";
yii\bootstrap5\Modal::end();

