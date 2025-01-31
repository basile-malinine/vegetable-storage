<?php
use yii\bootstrap5\Nav;

echo Nav::widget([
    'options' => ['class' => 'navbar-nav ms-5'],
    'items' => [
        ['label' => 'Home', 'url' => ['/site/index']],
        ['label' => 'About', 'url' => ['/site/about']],
        ['label' => 'Contact', 'url' => ['/site/contact']],
    ]
]);
