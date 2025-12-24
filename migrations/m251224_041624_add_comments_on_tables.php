<?php

use yii\db\Migration;

class m251224_041624_add_comments_on_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addCommentOnTable('acceptance', 'Приёмки');
        $this->addCommentOnTable('acceptance_item', 'Позиции Приёмки');
        $this->addCommentOnTable('acceptance_status', 'Статусы Приёмки');
        $this->addCommentOnTable('acceptance_type', 'Типы Приёмки');
        $this->addCommentOnTable('assortment', 'Номенклатура');
        $this->addCommentOnTable('assortment_group', 'Классификатор Номенклатуры');
        $this->addCommentOnTable('auth_assignment', 'Назначение элементов авторизации');
        $this->addCommentOnTable('auth_item', 'Элементы авторизации');
        $this->addCommentOnTable('auth_item_child', 'Иерархия элементов авторизации');
        $this->addCommentOnTable('auth_rule', 'Правила авторизации');
        $this->addCommentOnTable('car_body', 'Типы Кузова');
        $this->addCommentOnTable('car_brand', 'Марки автомобилей');
        $this->addCommentOnTable('contractor', 'Исполнители');
        $this->addCommentOnTable('country', 'Страны');
        $this->addCommentOnTable('currency', 'Валюты');
        $this->addCommentOnTable('delivery', 'Поставки');
        $this->addCommentOnTable('delivery_item', 'Позиции Поставки');
        $this->addCommentOnTable('distribution_center', 'Распределительные центры');
        $this->addCommentOnTable('driver_status', 'Статусы Водителя');
        $this->addCommentOnTable('employee', 'Сотрудники');
        $this->addCommentOnTable('flight_type', 'Типы Рейсов');
        $this->addCommentOnTable('gate_type', 'Ворота / Рампы');
        $this->addCommentOnTable('google_sheet', 'Таблицы Google');
        $this->addCommentOnTable('legal_subject', 'Физ. / Юр. лица');
        $this->addCommentOnTable('location_status', 'Статусы Местоположения');
        $this->addCommentOnTable('manager', 'Менеджеры');
        $this->addCommentOnTable('migration', 'Таблица миграций');
        $this->addCommentOnTable('migration_rbac', 'Таблица миграций RBAC');
        $this->addCommentOnTable('opf', 'Организационно-правовые формы');
        $this->addCommentOnTable('order', 'Заказы');
        $this->addCommentOnTable('order_item', 'Позиции Заказа');
        $this->addCommentOnTable('order_status', 'Статусы Заказа');
        $this->addCommentOnTable('pallet_type', 'Типы Паллета');
        $this->addCommentOnTable('payment_method', 'Способы оплаты');
        $this->addCommentOnTable('refund', 'Возвраты');
        $this->addCommentOnTable('refund_item', 'Позиции Возврата');
        $this->addCommentOnTable('shipment_type', 'Типы Отгрузки');
        $this->addCommentOnTable('sticker_status', 'Статусы Стикера');
        $this->addCommentOnTable('stock', 'Склады');
        $this->addCommentOnTable('system_object', 'Объекты системы');
        $this->addCommentOnTable('system_object_google_sheet', 'Связь объектов с Google');
        $this->addCommentOnTable('temperature_regime', 'Температурные режимы');
        $this->addCommentOnTable('unit', 'Единицы измерения');
        $this->addCommentOnTable('user', 'Пользователи');
        $this->addCommentOnTable('work_type', 'Виды работ');
        $this->addCommentOnTable('workshift', 'Смена');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }
}
