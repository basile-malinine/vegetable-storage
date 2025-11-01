<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%delivery}}`.
 */
class m251030_025219_create_delivery_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%delivery}}', [
            'id' => $this->primaryKey(),
            'supplier_id' => $this->integer()->notNull()->comment('Поставщик'),
            'own_id' => $this->integer()->notNull()->comment('Предприятие'),
            'stock_id' => $this->integer()->notNull()->comment('Склад'),
            'manager_id' => $this->integer()->notNull()->comment('Менеджер'),
            'date_wait' => $this->timestamp()->null()->defaultValue(null)->comment('Дата ожидания'),
            'date_close' => $this->timestamp()->null()->defaultValue(null)->comment('Дата закрытия'),
            'comment' => $this->text()->null()->defaultValue(null)->comment('Комментарий'),
            'created_by' => $this->integer()->comment('Создатель'),
            'created_at' => $this->timestamp()->comment('Дата создания'),
            'updated_at' => $this->timestamp()->comment('Дата обновления'),
        ]);

        $this->createIndex(
            '{{%idx-delivery-supplier_id}}',
            '{{%delivery}}',
            'supplier_id'
        );
        $this->addForeignKey(
            '{{%fk-delivery-supplier_id}}',
            '{{%delivery}}',
            'supplier_id',
            '{{%legal_subject}}',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        $this->createIndex(
            '{{%idx-delivery-own_id}}',
            '{{%delivery}}',
            'own_id'
        );
        $this->addForeignKey(
            '{{%fk-delivery-own_id}}',
            '{{%delivery}}',
            'own_id',
            '{{%legal_subject}}',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        $this->createIndex(
            '{{%idx-delivery-stock_id}}',
            '{{%delivery}}',
            'stock_id'
        );
        $this->addForeignKey(
            '{{%fk-delivery-stock_id}}',
            '{{%delivery}}',
            'stock_id',
            '{{%stock}}',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        $this->createIndex(
            '{{%idx-delivery-manager_id}}',
            '{{%delivery}}',
            'manager_id'
        );
        $this->addForeignKey(
            '{{%fk-delivery-manager_id}}',
            '{{%delivery}}',
            'manager_id',
            '{{%manager}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-delivery-manager_id}}', '{{%delivery}}');
        $this->dropIndex('{{%idx-delivery-manager_id}}', '{{%delivery}}');

        $this->dropForeignKey('{{%fk-delivery-stock_id}}', '{{%delivery}}');
        $this->dropIndex('{{%idx-delivery-stock_id}}', '{{%delivery}}');

        $this->dropForeignKey('{{%fk-delivery-own_id}}', '{{%delivery}}');
        $this->dropIndex('{{%idx-delivery-own_id}}', '{{%delivery}}');

        $this->dropForeignKey('{{%fk-delivery-supplier_id}}', '{{%delivery}}');
        $this->dropIndex('{{%idx-delivery-supplier_id}}', '{{%delivery}}');

        $this->dropTable('{{%delivery}}');
    }
}
