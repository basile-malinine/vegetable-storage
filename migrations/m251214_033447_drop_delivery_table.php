<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%delivery}}`.
 */
class m251214_033447_drop_delivery_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // drop delivery-item -------------------------------------------------------
        $this->dropTable('{{%delivery_item}}');

        // drop delivery ------------------------------------------------------------
        $this->dropTable('{{%delivery}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // create delivery ------------------------------------------------------------

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

        $this->createIndex('{{%idx-delivery-supplier_id}}', '{{%delivery}}', 'supplier_id');
        $this->addForeignKey(
            '{{%fk-delivery-supplier_id}}',
            '{{%delivery}}',
            'supplier_id',
            '{{%legal_subject}}',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        $this->createIndex('{{%idx-delivery-own_id}}', '{{%delivery}}', 'own_id');
        $this->addForeignKey(
            '{{%fk-delivery-own_id}}',
            '{{%delivery}}',
            'own_id',
            '{{%legal_subject}}',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

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

        $this->createIndex('{{%idx-delivery-manager_id}}', '{{%delivery}}', 'manager_id');
        $this->addForeignKey(
            '{{%fk-delivery-manager_id}}',
            '{{%delivery}}',
            'manager_id',
            '{{%manager}}',
            'id'
        );

        // create delivery-item -------------------------------------------------------

        $this->createTable('{{%delivery_item}}', [
            'id' => $this->primaryKey(),
            'delivery_id' => $this->integer()->comment('Доставка'),
            'assortment_id' => $this->integer()->notNull()->comment('Номенклатурная позиция'),
            'quantity' => $this->integer()->notNull()->comment('Количество'),
            'price' => $this->decimal(8, 2)->notNull()->comment('Цена'),
        ]);

        $this->createIndex('{{%idx-delivery_item-delivery_id}}', '{{%delivery_item}}', 'delivery_id');
        $this->addForeignKey(
            '{{%fk-delivery_item-delivery_id}}',
            '{{%delivery_item}}',
            'delivery_id',
            '{{%delivery}}',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        $this->createIndex('{{%idx-delivery_item-assortment_id}}', '{{%delivery_item}}', 'assortment_id');
        $this->addForeignKey(
            '{{%fk-delivery_item-assortment_id}}',
            '{{%delivery_item}}',
            'assortment_id',
            '{{%assortment}}',
            'id',
            'NO ACTION',
            'NO ACTION'
        );
    }
}
