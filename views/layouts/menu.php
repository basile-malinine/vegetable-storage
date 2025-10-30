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
                    'label' => 'Страны',
                    'url' => ['/country'],
                ],

                [
                    'label' => 'Единицы измерения',
                    'url' => ['/unit'],
                ],

                '<hr class="dropdown-divider">',

                [
                    'label' => 'Контрагенты',
                    'url' => ['/legal-subject'],
                ],

                [
                    'label' => 'Собственные предприятия',
                    'url' => ['/legal-subject-own'],
                ],

                [
                    'label' => 'Склады',
                    'url' => ['/stock'],
                ],

                [
                    'label' => 'Менеджеры',
                    'url' => ['/manager'],
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

                '<hr class="dropdown-divider">',

                [
                    'label' => 'Виды работ',
                    'url' => ['/work-type'],
                ],

                [
                    'label' => 'Исполнители',
                    'url' => ['/contractor'],
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
