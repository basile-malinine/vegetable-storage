<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'name' => 'АПК-ТЕХНОЛОГИИ',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru-RU',
    'timeZone' => 'Europe/Moscow',

    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages', // if advanced application, set @frontend/messages
                    'sourceLanguage' => 'en',
                    'fileMap' => [
                        //'main' => 'main.php',
                    ],
                ],
            ],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'x7RQ0DqHYKr4c2qlN1bEZSP_GEwF-wHx',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,

        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,

            'rules' => [
                // UnitController Единицы измерения
                'unit/<action:(edit|delete)>/<id:\d+>' => 'unit/<action>',

                // ProductController Продукты
                'product/<action:(edit|delete)>/<id:\d+>' => 'product/<action>',

                // AssortmentController Номенклатура
                'assortment/<action:(edit|delete)>/<id:\d+>' => 'assortment/<action>',

                // CountryController Страны
                'country/<action:(edit|delete)>/<id:\d+>' => 'country/<action>',

                // AcceptanceTypeController Типы приемки
                'acceptance-type/<action:(edit|delete)>/<id:\d+>' => 'acceptance-type/<action>',

                // UserController Пользователи
                'user/<action:(edit|delete)>/<id:\d+>' => 'user/<action>',

                // ShipmentTypeController Типы отгрузки
                'shipment-type/<action:(edit|delete)>/<id:\d+>' => 'shipment-type/<action>',

                // WorkTypeController Виды работ
                'work-type/<action:(edit|delete)>/<id:\d+>' => 'work-type/<action>',

                // ContractorController Исполнители
                'contractor/<action:(edit|delete)>/<id:\d+>' => 'contractor/<action>',

                // RbacController Роли и Разрешения
                'rbac/<action:(edit-role|remove-role)>/<name:\w+>' => 'rbac/<action>',
                'rbac/<action:(user)>/<id:\d+>' => 'rbac/<action>',

                // LegalSubjectController Контрагенты
                'legal-subject/<action:(edit|delete)>/<id:\d+>' => 'legal-subject/<action>',

                // LegalSubjectOwnController Собственные предприятия
                'legal-subject-own/<action:(edit|delete)>/<id:\d+>' => 'legal-subject-own/<action>',

                // ContractorController Менеджеры
                'manager/<action:(edit|delete)>/<id:\d+>' => 'manager/<action>',

                // StockController Склады
                'stock/<action:(edit|delete)>/<id:\d+>' => 'stock/<action>',

                // DeliveryController Доставки
                'delivery/<action:(edit|delete)>/<id:\d+>' => 'delivery/<action>',

                // DeliveryItemController Позиция по Доставке
                'delivery-item/<action:(add|edit)>/<id:\d+>' => 'delivery-item/<action>',

                // EmployeeController Сотрудники
                'employee/<action:(edit|delete)>/<id:\d+>' => 'employee/<action>',

                // FlightTypeController Тип рейса
                'flight-type/<action:(edit|delete)>/<id:\d+>' => 'flight-type/<action>',

                // GateTypeController Ворота / Рампы
                'gate-type/<action:(edit|delete)>/<id:\d+>' => 'gate-type/<action>',

                // PalletTypeController Типы паллет
                'pallet-type/<action:(edit|delete)>/<id:\d+>' => 'pallet-type/<action>',

                // WorkshiftController Смена
                'workshift/<action:(edit|delete)>/<id:\d+>' => 'workshift/<action>',

                // TemperatureRegimeController Температурные режимы
                'temperature-regime/<action:(edit|delete)>/<id:\d+>' => 'temperature-regime/<action>',

                // CarBrandController Марки автомобилей
                'car-brand/<action:(edit|delete)>/<id:\d+>' => 'car-brand/<action>',

                // CarBodyController Типы кузова
                'car-body/<action:(edit|delete)>/<id:\d+>' => 'car-body/<action>',

                // DriverStatusController Статус Водитель
                'driver-status/<action:(edit|delete)>/<id:\d+>' => 'driver-status/<action>',

                // LocationStatusController Статус Местоположение
                'location-status/<action:(edit|delete)>/<id:\d+>' => 'location-status/<action>',

                // StickerStatusController Статус Стикер
                'sticker-status/<action:(edit|delete)>/<id:\d+>' => 'sticker-status/<action>',

                // OrderStatusController Статус Заказ
                'order-status/<action:(edit|delete)>/<id:\d+>' => 'order-status/<action>',

                // AcceptanceStatusController Статус Приёмка
                'acceptance-status/<action:(edit|delete)>/<id:\d+>' => 'acceptance-status/<action>',

                // GoogleSheetController Таблицы Google
                'google-sheet/<action:(edit|delete)>/<id:\d+>' => 'google-sheet/<action>',

                //AssortmentGroupController Классификатор номенклатуры
                'assortment-group/<action:(index|create)>/<parent_id:\d+>' => 'assortment-group/<action>',
                'assortment-group/<action:(edit|delete)>/<id:\d+>' => 'assortment-group/<action>',
                'assortment-group/<action:(edit|delete)>/<id:\d+>/<parent_id:\d+>' => 'assortment-group/<action>',
            ],
        ],
    ],
    'params' => $params,
    'container' => [
        'definitions' => [
            yii\grid\GridView::class => [
                'tableOptions' => [
                    'class' => 'table table-condensed table-striped table-bordered table-hover mt-2'
                ],
                'formatter' => [
                    'class' => 'yii\i18n\Formatter',
                    'nullDisplay' => '',
                ],
            ],
        ],
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
