<?php

use yii\bootstrap5\Nav;
use app\models\Company\Company;
use app\models\LegalSubject\LegalSubject;
use app\models\Product\Product;

echo Nav::widget([
    'options' => ['class' => 'navbar-nav ms-5'],
    'encodeLabels' => false,

    'items' => [
        [
            'label' => 'Справочники',
            'items' => [
                [
                    'label' => 'Единицы измерения',
                    'url' => ['/unit'],
                ],

                '<hr class="dropdown-divider">',

                [
                    'label' => 'Продукты',
                    'url' => ['/product'],
                ],

                [
                    'label' => 'Номенклатура',
                    'url' => ['/assortment'],
                ],

                '<hr class="dropdown-divider">',

                [
                    'label' => 'Страны',
                    'url' => ['/country'],
                ],
            ],
        ],
    ]
]);
