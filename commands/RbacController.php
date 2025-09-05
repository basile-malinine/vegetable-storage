<?php

namespace app\commands;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $authManager = Yii::$app->authManager;
        $authManager->removeAll();

        // Роль Администратор
        $admin = $authManager->createRole('admin');
        $admin->description = 'Администратор';
        $authManager->add($admin);

        // Разрешения для справочника Единицы измерения ----------------------------------

        $permission = $authManager->createPermission('unit::list');      // Просмотр списка
        $permission->description = 'Единицы измерения :: Просмотр списка';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('unit::create');    // Добавление
        $permission->description = 'Единицы измерения :: Добавление';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('unit::edit');      // Редактирование
        $permission->description = 'Единицы измерения :: Редактирование';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('unit::delete');    // Удаление
        $permission->description = 'Единицы измерения :: Удаление';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        // Разрешения для справочника Продукты ----------------------------------

        $permission = $authManager->createPermission('product::list');      // Просмотр списка
        $permission->description = 'Продукты :: Просмотр списка';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('product::create');    // Добавление
        $permission->description = 'Продукты :: Добавление';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('product::edit');      // Редактирование
        $permission->description = 'Продукты :: Редактирование';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('product::delete');    // Удаление
        $permission->description = 'Продукты :: Удаление';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        // Разрешения для справочника Номенклатура ----------------------------------

        $permission = $authManager->createPermission('assortment::list');      // Просмотр списка
        $permission->description = 'Номенклатура :: Просмотр списка';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('assortment::create');    // Добавление
        $permission->description = 'Номенклатура :: Добавление';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('assortment::edit');      // Редактирование
        $permission->description = 'Номенклатура :: Редактирование';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('assortment::delete');    // Удаление
        $permission->description = 'Номенклатура :: Удаление';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        // Разрешения для справочника Страны ----------------------------------

        $permission = $authManager->createPermission('country::list');      // Просмотр списка
        $permission->description = 'Страны :: Просмотр списка';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('country::create');    // Добавление
        $permission->description = 'Страны :: Добавление';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('country::edit');      // Редактирование
        $permission->description = 'Страны :: Редактирование';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('country::delete');    // Удаление
        $permission->description = 'Страны :: Удаление';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        // Разрешения для справочника Типы приемки ----------------------------------

        $permission = $authManager->createPermission('acceptance_type::list');      // Просмотр списка
        $permission->description = 'Типы приемки :: Просмотр списка';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('acceptance_type::create');    // Добавление
        $permission->description = 'Типы приемки :: Добавление';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('acceptance_type::edit');      // Редактирование
        $permission->description = 'Типы приемки :: Редактирование';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('acceptance_type::delete');    // Удаление
        $permission->description = 'Типы приемки :: Удаление';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        // Разрешения для справочника Типы отгрузки ----------------------------------

        $permission = $authManager->createPermission('shipment_type::list');      // Просмотр списка
        $permission->description = 'Типы отгрузки :: Просмотр списка';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('shipment_type::create');    // Добавление
        $permission->description = 'Типы отгрузки :: Добавление';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('shipment_type::edit');      // Редактирование
        $permission->description = 'Типы отгрузки :: Редактирование';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('shipment_type::delete');    // Удаление
        $permission->description = 'Типы отгрузки :: Удаление';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        // Назначение роли Администратор Пользователю с ID === 1 (по умолчанию admin)
        $authManager->assign($admin, 1);
        $authManager->invalidateCache();
    }
}