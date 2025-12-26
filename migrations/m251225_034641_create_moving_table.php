<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%moving}}`.
 */
class m251225_034641_create_moving_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%moving}}', [
            'id' => $this->primaryKey(),
            'acceptance_id' => $this->integer()->notNull()->comment('Приёмка'),
            'company_sender_id' => $this->integer()->notNull()->comment('Предприятие отправитель'),
            'stock_sender_id' => $this->integer()->notNull()->comment('Склад отправитель'),
            'company_recipient_id' => $this->integer()->notNull()->comment('Предприятие получатель'),
            'stock_recipient_id' => $this->integer()->notNull()->comment('Склад получатель'),
            'moving_date' => $this->timestamp()->notNull()->comment('Дата перемещения'),
            'date_close' => $this->timestamp()->null()->comment('Дата закрытия'),
            'comment' => $this->text()->null()->comment('Комментарий'),
            'created_by' => $this->integer()->comment('Создатель'),
            'created_at' => $this->timestamp()->comment('Дата создания'),
            'updated_at' => $this->timestamp()->comment('Дата обновления'),
        ]);
        $this->addCommentOnTable('moving', 'Перемещения');

        // Приёмка -------------------------------------------------------------
        $this->createIndex('idx-moving-acceptance_id', 'moving', 'acceptance_id');
        $this->addForeignKey(
            'fk-moving-acceptance_id',
            'moving',
            'acceptance_id',
            'acceptance',
            'id',
            'NO ACTION'
        );

        // Предприятие отправитель ---------------------------------------------
        $this->createIndex('idx-moving-company_sender_id', 'moving', 'company_sender_id');
        $this->addForeignKey(
            'fk-moving-company_sender_id',
            'moving',
            'company_sender_id',
            'legal_subject',
            'id',
            'NO ACTION'
        );

        // Склад отправитель ---------------------------------------------------
        $this->createIndex('idx-moving-stock_sender_id', 'moving', 'stock_sender_id');
        $this->addForeignKey(
            'fk-moving-stock_sender_id',
            'moving',
            'stock_sender_id',
            'stock',
            'id',
            'NO ACTION'
        );

        // Предприятие получатель ----------------------------------------------
        $this->createIndex('idx-moving-company_recipient_id', 'moving', 'company_recipient_id');
        $this->addForeignKey(
            'fk-moving-company_recipient_id',
            'moving',
            'company_recipient_id',
            'legal_subject',
            'id',
            'NO ACTION'
        );

        // Склад получатель ----------------------------------------------------
        $this->createIndex('idx-moving-stock_recipient_id', 'moving', 'stock_recipient_id');
        $this->addForeignKey(
            'fk-moving-stock_recipient_id',
            'moving',
            'stock_recipient_id',
            'stock',
            'id',
            'NO ACTION'
        );

        // Создатель -----------------------------------------------------------
        $this->createIndex('idx-moving-created_by', 'moving', 'created_by');
        $this->addForeignKey(
            'fk-moving-created_by',
            'moving',
            'created_by',
            'user',
            'id',
            'NO ACTION');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%moving}}');
    }
}
