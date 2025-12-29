<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%shipment}}`.
 */
class m251226_104132_create_shipment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%shipment}}', [
            'id' => $this->primaryKey(),
            'type_id' => $this->integer()->notNull()->comment('Тип отгрузки'),
            'delivery_id' => $this->integer()->null()->comment('Поставка'),
            'parent_doc_id' => $this->integer()->notNull()->comment('Старший документ'),
            'company_own_id' => $this->integer()->notNull()->comment('Предприятие'),
            'stock_id' => $this->integer()->notNull()->comment('Склад'),
            'shipment_date' => $this->timestamp()->notNull()->comment('Дата отгрузки'),
            'date_close' => $this->timestamp()->null()->comment('Дата закрытия'),
            'comment' => $this->text()->null()->comment('Комментарий'),
            'created_by' => $this->integer()->comment('Создатель'),
            'created_at' => $this->timestamp()->comment('Дата создания'),
            'updated_at' => $this->timestamp()->comment('Дата обновления'),
        ]);
        $this->addCommentOnTable('shipment', 'Отгрузки');

        // Поставка ------------------------------------------------------------
        $this->createIndex('{{%idx-shipment-delivery_id}}', '{{%shipment}}', 'delivery_id');
        $this->addForeignKey(
            '{{%fk-shipment-delivery_id}}',
            '{{%shipment}}',
            'delivery_id',
            '{{%delivery}}',
            'id',
            'NO ACTION',
        );

        // Старший документ ----------------------------------------------------
        $this->createIndex('{{%idx-shipment-parent_doc_id}}', '{{%shipment}}', 'parent_doc_id');

        // Предприятие ---------------------------------------------------------
        $this->createIndex('{{%idx-shipment-company_own_id}}', '{{%shipment}}', 'company_own_id');
        $this->addForeignKey(
            '{{%fk-shipment-company_own_id}}',
            '{{%shipment}}',
            'company_own_id',
            '{{%legal_subject}}',
            'id',
            'NO ACTION',
        );

        // Склад ---------------------------------------------------------------
        $this->createIndex('{{%idx-shipment-stock_id}}', '{{%shipment}}', 'stock_id');
        $this->addForeignKey(
            '{{%fk-shipment-stock_id}}',
            '{{%shipment}}',
            'stock_id',
            '{{%stock}}',
            'id',
            'NO ACTION',
            'NO ACTION'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%shipment}}');
    }
}
