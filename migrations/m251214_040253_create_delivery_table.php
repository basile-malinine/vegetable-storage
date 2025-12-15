<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%delivery}}`.
 */
class m251214_040253_create_delivery_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%delivery}}', [
            'id' => $this->primaryKey(),
            'type_id' => $this->integer()->notNull()->comment('Тип поставки'),
            'supplier_id' => $this->integer()->notNull()->comment('Поставщик'),
            'company_own_id' => $this->integer()->notNull()->comment('Предприятие'),
            'stock_id' => $this->integer()->null()->comment('Склад'),
            'executor_id' => $this->integer()->null()->comment('Исполнитель'),
            'purchasing_mng_id' => $this->integer()->notNull()->comment('Менеджер по закупкам'),
            'purchasing_agent_id' => $this->integer()->null()->comment('Агент по закупкам'),
            'sales_mng_id' => $this->integer()->notNull()->comment('Менеджер по реализации'),
            'support_mng_id' => $this->integer()->null()->comment('Отдел сопровождения'),
            'currency_id' => $this->integer()->notNull()->comment('Валюта'),
            'payment_method_id' => $this->integer()->notNull()->comment('Способ оплаты'),
            'transport_affiliation_id' => $this->integer()->notNull()->comment('Ставит транспорт'),
            'shipment_date' => $this->timestamp()->null()->comment('Дата отгрузки'),
            'unloading_date' => $this->timestamp()->null()->comment('Дата выгрузки'),
            'payment_term' => $this->timestamp()->null()->comment('Срок оплаты'),
            'date_close' => $this->timestamp()->null()->comment('Дата закрытия'),
            'comment' => $this->text()->null()->comment('Комментарий'),
            'created_by' => $this->integer()->comment('Создатель'),
            'created_at' => $this->timestamp()->comment('Дата создания'),
            'updated_at' => $this->timestamp()->comment('Дата обновления'),
        ]);

        // Поставщик -----------------------------------------------------------
        $this->createIndex('{{%idx-delivery-supplier_id}}', '{{%delivery}}', 'supplier_id');
        $this->addForeignKey(
            '{{%fk-delivery-supplier_id}}',
            '{{%delivery}}',
            'supplier_id',
            '{{%legal_subject}}',
            'id',
            'NO ACTION'
        );

        // Предприятие ---------------------------------------------------------
        $this->createIndex('{{%idx-delivery-company_own_id}}', '{{%delivery}}', 'company_own_id');
        $this->addForeignKey(
            '{{%fk-delivery-company_own_id}}',
            '{{%delivery}}',
            'company_own_id',
            '{{%legal_subject}}',
            'id',
            'NO ACTION',
        );

        // Склад ---------------------------------------------------------------
        $this->createIndex('{{%idx-delivery-stock_id}}', '{{%delivery}}', 'stock_id');
        $this->addForeignKey(
            '{{%fk-delivery-stock_id}}',
            '{{%delivery}}',
            'stock_id',
            '{{%stock}}',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        // Исполнитель ---------------------------------------------------------
        $this->createIndex('{{%idx-delivery-executor_id}}', '{{%delivery}}', 'executor_id');
        $this->addForeignKey(
            '{{%fk-delivery-executor_id}}',
            '{{%delivery}}',
            'executor_id',
            '{{%manager}}',
            'id',
            'NO ACTION'
        );

        // Менеджер по закупкам ------------------------------------------------
        $this->createIndex('{{%idx-delivery-purchasing_mng_id}}', '{{%delivery}}', 'purchasing_mng_id');
        $this->addForeignKey(
            '{{%fk-delivery-purchasing_mng_id}}',
            '{{%delivery}}',
            'purchasing_mng_id',
            '{{%manager}}',
            'id',
            'NO ACTION'
        );

        // Агент по закупкам ---------------------------------------------------
        $this->createIndex('{{%idx-delivery-purchasing_agent_id}}', '{{%delivery}}', 'purchasing_agent_id');
        $this->addForeignKey(
            '{{%fk-delivery-purchasing_agent_id}}',
            '{{%delivery}}',
            'purchasing_agent_id',
            '{{%manager}}',
            'id',
            'NO ACTION'
        );

        // Отдел сопровождения -------------------------------------------------
        $this->createIndex('{{%idx-delivery-support_mng_id}}', '{{%delivery}}', 'support_mng_id');
        $this->addForeignKey(
            '{{%fk-delivery-support_mng_id}}',
            '{{%delivery}}',
            'support_mng_id',
            '{{%manager}}',
            'id',
            'NO ACTION'
        );

        // Менеджер по продажам ------------------------------------------------
        $this->createIndex('{{%idx-delivery-sales_mng_id}}', '{{%delivery}}', 'sales_mng_id');
        $this->addForeignKey(
            '{{%fk-delivery-sales_mng_id}}',
            '{{%delivery}}',
            'sales_mng_id',
            '{{%manager}}',
            'id',
            'NO ACTION'
        );

        // Валюта ------ -------------------------------------------------------
        $this->createIndex('{{%idx-delivery-currency_id}}', '{{%delivery}}', 'currency_id');
        $this->addForeignKey(
            '{{%fk-delivery-currency_id}}',
            '{{%delivery}}',
            'currency_id',
            '{{%currency}}',
            'id',
            'NO ACTION'
        );

        // Способ оплаты -------------------------------------------------------
        $this->createIndex('{{%idx-delivery-payment_method_id}}', '{{%delivery}}', 'payment_method_id');
        $this->addForeignKey(
            '{{%fk-delivery-payment_method_id}}',
            '{{%delivery}}',
            'payment_method_id',
            '{{%payment_method}}',
            'id',
            'NO ACTION'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%delivery}}');
    }
}
