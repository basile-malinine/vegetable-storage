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

        $permission = $authManager->createPermission('unit.list');      // Просмотр списка
        $permission->description = 'Справочники :: Единицы измерения :: Просмотр списка';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('unit.create');    // Добавление
        $permission->description = 'Справочники :: Единицы измерения :: Добавление';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('unit.edit');      // Редактирование
        $permission->description = 'Справочники :: Единицы измерения :: Редактирование';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('unit.delete');    // Удаление
        $permission->description = 'Справочники :: Единицы измерения :: Удаление';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        // Разрешения для справочника Продукты ----------------------------------

        $permission = $authManager->createPermission('product.list');      // Просмотр списка
        $permission->description = 'Справочники :: Продукты :: Просмотр списка';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('product.create');    // Добавление
        $permission->description = 'Справочники :: Продукты :: Добавление';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('product.edit');      // Редактирование
        $permission->description = 'Справочники :: Продукты :: Редактирование';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('product.delete');    // Удаление
        $permission->description = 'Справочники :: Продукты :: Удаление';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        // Разрешения для справочника Номенклатура ----------------------------------

        $permission = $authManager->createPermission('assortment.list');      // Просмотр списка
        $permission->description = 'Справочники :: Номенклатура :: Просмотр списка';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('assortment.create');    // Добавление
        $permission->description = 'Справочники :: Номенклатура :: Добавление';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('assortment.edit');      // Редактирование
        $permission->description = 'Справочники :: Номенклатура :: Редактирование';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('assortment.delete');    // Удаление
        $permission->description = 'Справочники :: Номенклатура :: Удаление';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        // Разрешения для справочника Страны ----------------------------------

        $permission = $authManager->createPermission('country.list');      // Просмотр списка
        $permission->description = 'Справочники :: Страны :: Просмотр списка';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('country.create');    // Добавление
        $permission->description = 'Справочники :: Страны :: Добавление';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('country.edit');      // Редактирование
        $permission->description = 'Справочники :: Страны :: Редактирование';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('country.delete');    // Удаление
        $permission->description = 'Справочники :: Страны :: Удаление';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        // Разрешения для справочника Типы приемки ----------------------------------

        $permission = $authManager->createPermission('acceptance_type.list');      // Просмотр списка
        $permission->description = 'Справочники :: Типы приемки :: Просмотр списка';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('acceptance_type.create');    // Добавление
        $permission->description = 'Справочники :: Типы приемки :: Добавление';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('acceptance_type.edit');      // Редактирование
        $permission->description = 'Справочники :: Типы приемки :: Редактирование';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('acceptance_type.delete');    // Удаление
        $permission->description = 'Справочники :: Типы приемки :: Удаление';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        // Разрешения для справочника Типы отгрузки ----------------------------------

        $permission = $authManager->createPermission('shipment_type.list');      // Просмотр списка
        $permission->description = 'Справочники :: Типы отгрузки :: Просмотр списка';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('shipment_type.create');    // Добавление
        $permission->description = 'Справочники :: Типы отгрузки :: Добавление';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('shipment_type.edit');      // Редактирование
        $permission->description = 'Справочники :: Типы отгрузки :: Редактирование';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('shipment_type.delete');    // Удаление
        $permission->description = 'Справочники :: Типы отгрузки :: Удаление';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        // Разрешения для справочника Виды работ -------------------------------------

        $permission = $authManager->createPermission('work_type.list');      // Просмотр списка
        $permission->description = 'Справочники :: Виды работ :: Просмотр списка';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('work_type.create');    // Добавление
        $permission->description = 'Справочники :: Виды работ :: Добавление';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('work_type.edit');      // Редактирование
        $permission->description = 'Справочники :: Виды работ :: Редактирование';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('work_type.delete');    // Удаление
        $permission->description = 'Справочники :: Виды работ :: Удаление';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        // Разрешения для справочника Исполнители ------------------------------------

        $permission = $authManager->createPermission('contractor.list');      // Просмотр списка
        $permission->description = 'Справочники :: Исполнители :: Просмотр списка';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('contractor.create');    // Добавление
        $permission->description = 'Справочники :: Исполнители :: Добавление';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('contractor.edit');      // Редактирование
        $permission->description = 'Справочники :: Исполнители :: Редактирование';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('contractor.delete');    // Удаление
        $permission->description = 'Справочники :: Исполнители :: Удаление';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        // Разрешения для справочника Менеджеры -------------------------------------

        $permission = $authManager->createPermission('manager.list');      // Просмотр списка
        $permission->description = 'Справочники :: Менеджеры :: Просмотр списка';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('manager.create');    // Добавление
        $permission->description = 'Справочники :: Менеджеры :: Добавление';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('manager.edit');      // Редактирование
        $permission->description = 'Справочники :: Менеджеры :: Редактирование';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('manager.delete');    // Удаление
        $permission->description = 'Справочники :: Менеджеры :: Удаление';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        // Разрешения для справочника Контрагенты ------------------------------------

        $permission = $authManager->createPermission('legal_subject.list');      // Просмотр списка
        $permission->description = 'Справочники :: Контрагенты :: Просмотр списка';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('legal_subject.create');    // Добавление
        $permission->description = 'Справочники :: Контрагенты :: Добавление';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('legal_subject.edit');      // Редактирование
        $permission->description = 'Справочники :: Контрагенты :: Редактирование';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('legal_subject.delete');    // Удаление
        $permission->description = 'Справочники :: Контрагенты :: Удаление';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        // Разрешения для справочника Собственные предприятия -----------------------

        $permission = $authManager->createPermission('legal_subject_own.list');      // Просмотр списка
        $permission->description = 'Справочники :: Собственные предприятия :: Просмотр списка';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('legal_subject_own.create');    // Добавление
        $permission->description = 'Справочники :: Собственные предприятия :: Добавление';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('legal_subject_own.edit');      // Редактирование
        $permission->description = 'Справочники :: Собственные предприятия :: Редактирование';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('legal_subject_own.delete');    // Удаление
        $permission->description = 'Справочники :: Собственные предприятия :: Удаление';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        // Разрешения для справочника Склады ----------------------------------------

        $permission = $authManager->createPermission('stock.list');      // Просмотр списка
        $permission->description = 'Справочники :: Склады :: Просмотр списка';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('stock.create');    // Добавление
        $permission->description = 'Справочники :: Склады :: Добавление';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('stock.edit');      // Редактирование
        $permission->description = 'Справочники :: Склады :: Редактирование';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('stock.delete');    // Удаление
        $permission->description = 'Справочники :: Склады :: Удаление';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        // Разрешения для управления Пользователями ----------------------------------

        $permission = $authManager->createPermission('user.list');      // Просмотр списка
        $permission->description = 'Управление :: Пользователи :: Просмотр списка';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('user.create');    // Добавление
        $permission->description = 'Управление :: Пользователи :: Добавление';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('user.edit');      // Редактирование
        $permission->description = 'Управление :: Пользователи :: Редактирование';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        $permission = $authManager->createPermission('user.delete');    // Удаление
        $permission->description = 'Управление :: Пользователи :: Удаление';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        // Разрешения для управления Ролями ------------------------------------------

        $permission = $authManager->createPermission('role.access');      // Полный доступ
        $permission->description = 'Управление :: Роли :: Полный доступ';
        $authManager->add($permission);
        // Разрешаем Администратору
        $authManager->addChild($admin, $permission);

        // Назначение роли Администратор Пользователю с ID === 1 (по умолчанию admin)
        $authManager->assign($admin, 1);
        $authManager->invalidateCache();
    }
}