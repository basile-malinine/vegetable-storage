<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%order}}`.
 */
class m251208_160738_create_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%order}}', [
            'id' => $this->primaryKey(),
            'type_id' => $this->integer()->notNull()->comment('Tип заказа'),
            'supplier_id' => $this->integer()->notNull()->comment('Поставщик'),
            'buyer_id' => $this->integer()->notNull()->comment('Сеть'),
            'distribution_center_id' => $this->integer()->notNull()->comment('Распределительный центр'),
            'stock_id' => $this->integer()->null()->defaultValue(null)->comment('Склад'),
            'executor_id' => $this->integer()->null()->defaultValue(null)->comment('Исполнитель'),
            'sales_mng_id' => $this->integer()->notNull()->comment('Менеджер по реализации'),
            'sales_agent_id' => $this->integer()->notNull()->comment('Агент по реализации'),
            'status_main_id' => $this->integer()->null()->comment('Статус учёта'),
            'status_additional_id' => $this->integer()->null()->comment('Статус реализации'),
            'date' => $this->timestamp()->notNull()->comment('Дата'),
            'date_close' => $this->timestamp()->null()->comment('Дата закрытия'),
            'comment' => $this->text()->null()->comment('Комментарий'),
            'created_by' => $this->integer()->comment('Создатель'),
            'created_at' => $this->timestamp()->comment('Дата создания'),
            'updated_at' => $this->timestamp()->comment('Дата обновления'),
        ]);


        // Поставщик -----------------------------------------------------------
        $this->createIndex('idx-order-supplier_id', '{{%order}}', 'supplier_id');
        $this->addForeignKey(
            'fk-order-supplier_id',
            '{{%order}}',
            'supplier_id',
            '{{%legal_subject}}',
            'id',
            'NO ACTION'
        );

        // Сеть ----------------------------------------------------------------
        $this->createIndex('idx-order-buyer_id', '{{%order}}', 'buyer_id');
        $this->addForeignKey(
            'fk-order-buyer_id',
            '{{%order}}',
            'buyer_id',
            '{{%legal_subject}}',
            'id',
            'NO ACTION'
        );

        // Распределительный центр ---------------------------------------------
        $this->createIndex('idx-order-distribution_center_id', '{{%order}}', 'distribution_center_id');
        $this->addForeignKey(
            'fk-order-distribution_center_id',
            '{{%order}}',
            'distribution_center_id',
            '{{%distribution_center}}',
            'id',
            'NO ACTION'
        );

        // Склад ---------------------------------------------------------------
        $this->createIndex('idx-order-stock_id', '{{%order}}', 'stock_id');
        $this->addForeignKey(
            'fk-order-stock_id',
            '{{%order}}',
            'stock_id',
            '{{%stock}}',
            'id',
            'NO ACTION'
        );

        // Исполнитель ---------------------------------------------------------
        $this->createIndex('idx-order-executor_id', '{{%order}}', 'executor_id');
        $this->addForeignKey(
            'fk-order-executor_id',
            '{{%order}}',
            'executor_id',
            '{{%manager}}',
            'id',
            'NO ACTION'
        );

        // Менеджер по реализации ----------------------------------------------
        $this->createIndex('idx-order-sales_mng_id', '{{%order}}', 'sales_mng_id');
        $this->addForeignKey(
            'fk-order-sales_mng_id',
            '{{%order}}',
            'sales_mng_id',
            '{{%manager}}',
            'id',
            'NO ACTION'
        );

        // Агент по реализации -------------------------------------------------
        $this->createIndex('idx-order-sales_agent_id', '{{%order}}', 'sales_agent_id');
        $this->addForeignKey(
            'fk-order-sales_agent_id',
            '{{%order}}',
            'sales_agent_id',
            '{{%manager}}',
            'id',
            'NO ACTION'
        );

        // Создатель -----------------------------------------------------------
        $this->createIndex('idx-order-created_by', '{{%order}}', 'created_by');
        $this->addForeignKey(
            'fk-order-created_by',
            '{{%order}}',
            'created_by',
            '{{%user}}',
            'id',
            'NO ACTION'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-order-created_by', '{{%order}}');
        $this->dropIndex('idx-order-created_by', '{{%order}}');

        $this->dropForeignKey('fk-order-sales_agent_id', '{{%order}}');
        $this->dropIndex('idx-order-sales_agent_id', '{{%order}}');

        $this->dropForeignKey('fk-order-sales_mng_id', '{{%order}}');
        $this->dropIndex('idx-order-sales_mng_id', '{{%order}}');

        $this->dropForeignKey('fk-order-executor_id', '{{%order}}');
        $this->dropIndex('idx-order-executor_id', '{{%order}}');

        $this->dropForeignKey('fk-order-stock_id', '{{%order}}');
        $this->dropIndex('idx-order-stock_id', '{{%order}}');

        $this->dropForeignKey('fk-order-distribution_center_id', '{{%order}}');
        $this->dropIndex('idx-order-distribution_center_id', '{{%order}}');

        $this->dropForeignKey('fk-order-buyer_id', '{{%order}}');
        $this->dropIndex('idx-order-buyer_id', '{{%order}}');

        $this->dropForeignKey('fk-order-supplier_id', '{{%order}}');
        $this->dropIndex('idx-order-supplier_id', '{{%order}}');

        $this->dropTable('{{%order}}');
    }
}
