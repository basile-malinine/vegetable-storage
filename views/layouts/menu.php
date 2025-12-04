<?php

use yii\bootstrap5\Nav;
use kartik\bs5dropdown\Dropdown;

echo Nav::widget([
    'dropdownClass' => Dropdown::class,
    'options' => ['class' => 'navbar-nav mr-auto me-auto'],
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

                [
                    'label' => 'Таблицы Google',
                    'url' => ['/google-sheet'],
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
                    'label' => 'Статусы',
                    'items' => [
                        [
                            'label' => 'Водитель +G',
                            'url' => ['/driver-status'],
                        ],

                        [
                            'label' => 'Местоположение +G',
                            'url' => ['/location-status'],
                        ],

                        [
                            'label' => 'Стикер +G',
                            'url' => ['/sticker-status'],
                        ],

                        [
                            'label' => 'Заказ +G',
                            'url' => ['/order-status'],
                        ],

                        [
                            'label' => 'Приёмка +G',
                            'url' => ['/acceptance-status'],
                        ],
                    ],
                ],

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
                    'url' => ['/car-brand'],
                ],

                [
                    'label' => 'Типы кузова',
                    'url' => ['/car-body'],
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
