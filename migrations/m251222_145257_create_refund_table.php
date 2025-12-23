<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%refund}}`.
 */
class m251222_145257_create_refund_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%refund}}', [
            'id' => $this->primaryKey(),
            'type_id' => $this->integer()->notNull()->comment('Тип возврата'),
            'order_id' => $this->integer()->notNull()->comment('Заказ'),
            'company_own_id' => $this->integer()->notNull()->comment('Предприятие'),
            'stock_id' => $this->integer()->notNull()->comment('Склад'),
            'refund_date' => $this->timestamp()->null()->comment('Дата возврата'),
            'date_close' => $this->timestamp()->null()->comment('Дата закрытия'),
            'comment' => $this->text()->null()->comment('Комментарий'),
            'created_by' => $this->integer()->comment('Создатель'),
            'created_at' => $this->timestamp()->comment('Дата создания'),
            'updated_at' => $this->timestamp()->comment('Дата обновления'),
        ]);

        // Заказ ---------------------------------------------------------------
        $this->createIndex('idx-refund-order_id', '{{%refund}}', 'order_id');
        $this->addForeignKey(
            'fk-refund-order_id',
            '{{%refund}}',
            'order_id',
            '{{%order}}',
            'id',
            'NO ACTION'
        );

        // Предприятие ---------------------------------------------------------
        $this->createIndex('idx-refund-company_own_id', '{{%refund}}', 'company_own_id');
        $this->addForeignKey(
            'fk-refund-company_own_id',
            '{{%refund}}',
            'company_own_id',
            '{{%legal_subject}}',
            'id',
            'NO ACTION'
        );

        // Склад ---------------------------------------------------------------
        $this->createIndex('idx-refund-stock_id', '{{%refund}}', 'stock_id');
        $this->addForeignKey(
            'fk-refund-stock_id',
            '{{%refund}}',
            'stock_id',
            '{{%stock}}',
            'id',
            'NO ACTION'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%refund}}');
    }
}
