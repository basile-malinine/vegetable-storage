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
                    'label' => 'Склады +G',
                    'url' => ['/stock'],
                ],

                [
                    'label' => 'Сотрудники',
                    'url' => ['/employee'],
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

                [
                    'label' => 'Типы рейсов +G',
                    'url' => ['/flight-type'],
                ],

                [
                    'label' => 'Ворота / Рампы +G',
                    'url' => ['/gate-type'],
                ],

                [
                    'label' => 'Типы паллет',
                    'url' => ['/pallet-type'],
                ],

                [
                    'label' => 'Смена',
                    'url' => ['/workshift'],
                ],

                [
                    'label' => 'Температурные режимы',
                    'url' => ['/temperature-regime'],
                ],

                [
                    'label' => 'Марки автомобилей',
                    'url' => ['/temperature-regime'],
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
            'label' => 'Документы',
            'options' => ['class' => 'ms-4'],
            'items' => [
                [
                    'label' => 'Доставки',
                    'url' => ['/delivery'],
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
