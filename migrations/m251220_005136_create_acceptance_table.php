<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%acceptance}}`.
 */
class m251220_005136_create_acceptance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%acceptance}}', [
            'id' => $this->primaryKey(),
            'type_id' => $this->integer()->notNull()->comment('Тип приёмки'),
            'delivery_id' => $this->integer()->null()->comment('Поставка'),
            'parent_doc_id' => $this->integer()->notNull()->comment('Старший документ'),
            'company_own_id' => $this->integer()->notNull()->comment('Предприятие'),
            'stock_id' => $this->integer()->notNull()->comment('Склад'),
            'acceptance_date' => $this->timestamp()->null()->comment('Дата приёмки'),
            'date_close' => $this->timestamp()->null()->comment('Дата закрытия'),
            'comment' => $this->text()->null()->comment('Комментарий'),
            'created_by' => $this->integer()->comment('Создатель'),
            'created_at' => $this->timestamp()->comment('Дата создания'),
            'updated_at' => $this->timestamp()->comment('Дата обновления'),
        ]);

        // Поставка ------------------------------------------------------------
        $this->createIndex('{{%idx-acceptance-delivery_id}}', '{{%acceptance}}', 'delivery_id');
        $this->addForeignKey(
            '{{%fk-acceptance-delivery_id}}',
            '{{%acceptance}}',
            'delivery_id',
            '{{%delivery}}',
            'id',
            'NO ACTION',
        );

        // Старший документ ----------------------------------------------------
        $this->createIndex('{{%idx-acceptance-parent_doc_id}}', '{{%acceptance}}', 'parent_doc_id');

        // Предприятие ---------------------------------------------------------
        $this->createIndex('{{%idx-acceptance-company_own_id}}', '{{%acceptance}}', 'company_own_id');
        $this->addForeignKey(
            '{{%fk-acceptance-company_own_id}}',
            '{{%acceptance}}',
            'company_own_id',
            '{{%legal_subject}}',
            'id',
            'NO ACTION',
        );

        // Склад ---------------------------------------------------------------
        $this->createIndex('{{%idx-acceptance-stock_id}}', '{{%acceptance}}', 'stock_id');
        $this->addForeignKey(
            '{{%fk-acceptance-stock_id}}',
            '{{%acceptance}}',
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
        $this->dropTable('{{%acceptance}}');
    }
}
