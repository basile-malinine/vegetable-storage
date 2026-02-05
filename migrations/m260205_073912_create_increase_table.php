<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%increase}}`.
 */
class m260205_073912_create_increase_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%increase}}', [
            'id' => $this->primaryKey(),
            'type_id' => $this->integer()->notNull()->comment('Тип оприходования'),
            'acceptance_id' => $this->integer()->notNull()->comment('Приёмка'),
            'company_own_id' => $this->integer()->notNull()->comment('Предприятие'),
            'stock_id' => $this->integer()->notNull()->comment('Склад'),
            'date' => $this->timestamp()->null()->comment('Дата оприходования'),
            'date_close' => $this->timestamp()->null()->comment('Дата закрытия'),
            'comment' => $this->text()->null()->comment('Комментарий'),
            'created_by' => $this->integer()->comment('Создатель'),
            'created_at' => $this->timestamp()->comment('Дата создания'),
            'updated_at' => $this->timestamp()->comment('Дата обновления'),
        ]);
        $this->addCommentOnTable('increase', 'Оприходование');

        // ----------------------------------------------------------- Приёмка
        $this->createIndex('idx-increase-acceptance_id', 'increase', 'acceptance_id');
        $this->addForeignKey(
            'fk-increase-acceptance_id',
            'increase',
            'acceptance_id',
            'acceptance',
            'id',
            'NO ACTION'
        );

        // ----------------------------------------------------------- Предприятие
        $this->createIndex('idx-increase-company_own_id', '{{%increase}}', 'company_own_id');
        $this->addForeignKey(
            'fk-increase-company_own_id',
            '{{%increase}}',
            'company_own_id',
            '{{%legal_subject}}',
            'id',
            'NO ACTION'
        );

        // ----------------------------------------------------------- Склад
        $this->createIndex('idx-increase-stock_id', '{{%increase}}', 'stock_id');
        $this->addForeignKey(
            'fk-increase-stock_id',
            '{{%increase}}',
            'stock_id',
            '{{%stock}}',
            'id',
            'NO ACTION'
        );

        // ----------------------------------------------------------- Создатель
        $this->createIndex('idx-increase-created_by', '{{%increase}}', 'created_by');
        $this->addForeignKey(
            'fk-increase-created_by',
            '{{%increase}}',
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
        $this->dropTable('{{%increase}}');
    }
}
