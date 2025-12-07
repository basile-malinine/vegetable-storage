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

                '<hr class="dropdown-divider">',

                [
                    'label' => 'Предприятия и сотрудники',
                    'items' => [
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
                    ],
                ],

//                '<hr class="dropdown-divider">',

                [
                    'label' => 'Номенклатура',
                    'items' => [
                        [
                            'label' => 'Классификатор номенклатуры',
                            'url' => ['/assortment-group'],
                        ],

                        [
                            'label' => 'Номенклатура',
                            'url' => ['/assortment'],
                        ],
                    ],
                ],

                '<hr class="dropdown-divider">',

                [
                    'label' => 'Статусы',
                    'items' => [
                        [
                            'label' => 'Статусы Водителя +G',
                            'url' => ['/driver-status'],
                        ],

                        [
                            'label' => 'Статусы Местоположения +G',
                            'url' => ['/location-status'],
                        ],

                        [
                            'label' => 'Статусы Стикера +G',
                            'url' => ['/sticker-status'],
                        ],

                        [
                            'label' => 'Статусы Заказа +G',
                            'url' => ['/order-status'],
                        ],

                        [
                            'label' => 'Статусы Приёмки +G',
                            'url' => ['/acceptance-status'],
                        ],
                    ],
                ],

                [
                    'label' => 'Типы',
                    'items' => [
                        [
                            'label' => 'Типы Приёмки',
                            'url' => ['/acceptance-type'],
                        ],

                        [
                            'label' => 'Типы Отгрузки',
                            'url' => ['/shipment-type'],
                        ],

                        [
                            'label' => 'Типы Рейсов +G',
                            'url' => ['/flight-type'],
                        ],

                        [
                            'label' => 'Типы Паллета',
                            'url' => ['/pallet-type'],
                        ],

                        [
                            'label' => 'Типы Кузова',
                            'url' => ['/car-body'],
                        ],
                    ],
                ],

                [
                    'label' => 'Ворота / Рампы +G',
                    'url' => ['/gate-type'],
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

                '<hr class="dropdown-divider">',

                [
                    'label' => 'Объекты системы',
                    'url' => ['/system-object'],
                ],

                [
                    'label' => 'Google',
                    'items' => [
                        [
                            'label' => 'Таблицы Google',
                            'url' => ['/google-sheet'],
                        ],

                        [
                            'label' => 'Связь объектов с Google',
                            'url' => ['/system-object-google-sheet'],
                        ],
                    ],
                ],
            ],
        ],
    ]
]);
