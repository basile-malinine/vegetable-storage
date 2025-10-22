<?php

use yii\bootstrap5\Nav;

echo Nav::widget([
    'options' => ['class' => 'navbar-nav'],
    'encodeLabels' => false,

    'items' => [
        [
            'label' => 'Справочники',
            'options' => ['class' => 'ms-4'],
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

                [
                    'label' => 'Типы отгрузки',
                    'url' => ['/shipment-type'],
                ],

                [
                    'label' => 'Виды работ',
                    'url' => ['/work-type'],
                ],

                '<hr class="dropdown-divider">',

                [
                    'label' => 'Исполнители',
                    'url' => ['/contractor'],
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
            'options' => ['class' => 'ms-4'],
            'items' => [
                [
                    'label' => 'Пользователи',
                    'url' => ['/user'],
                ],

                [
                    'label' => 'Роли',
                    'url' => ['/rbac'],
                ],
            ],
        ],
    ]
]);
