<?php

use yii\bootstrap5\Nav;

echo Nav::widget([
    'options' => ['class' => 'navbar-nav'],
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
                    'label' => 'Типы приемки',
                    'url' => ['/acceptance-type'],
                ],

                '<hr class="dropdown-divider">',

                [
                    'label' => 'Страны',
                    'url' => ['/country'],
                ],
            ],
        ],

        [
            'label' => 'Управление',
            'items' => [
                [
                    'label' => 'Пользователи',
                    'url' => ['/user'],
                ],
            ],
        ],
    ]
]);
