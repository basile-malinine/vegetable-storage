<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%decrease}}`.
 */
class m260201_005115_create_decrease_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%decrease}}', [
            'id' => $this->primaryKey(),
            'type_id' => $this->integer()->notNull()->comment('Тип списания'),
            'acceptance_id' => $this->integer()->notNull()->comment('Приёмка'),
            'company_own_id' => $this->integer()->notNull()->comment('Предприятие'),
            'stock_id' => $this->integer()->notNull()->comment('Склад'),
            'date' => $this->timestamp()->null()->comment('Дата списания'),
            'date_close' => $this->timestamp()->null()->comment('Дата закрытия'),
            'comment' => $this->text()->null()->comment('Комментарий'),
            'created_by' => $this->integer()->comment('Создатель'),
            'created_at' => $this->timestamp()->comment('Дата создания'),
            'updated_at' => $this->timestamp()->comment('Дата обновления'),
        ]);
        $this->addCommentOnTable('decrease', 'Списание');

        // ----------------------------------------------------------- Предприятие
        $this->createIndex('idx-decrease-company_own_id', '{{%decrease}}', 'company_own_id');
        $this->addForeignKey(
            'fk-decrease-company_own_id',
            '{{%decrease}}',
            'company_own_id',
            '{{%legal_subject}}',
            'id',
            'NO ACTION'
        );

        // ----------------------------------------------------------- Склад
        $this->createIndex('idx-decrease-stock_id', '{{%decrease}}', 'stock_id');
        $this->addForeignKey(
            'fk-decrease-stock_id',
            '{{%decrease}}',
            'stock_id',
            '{{%stock}}',
            'id',
            'NO ACTION'
        );

        // ----------------------------------------------------------- Склад
        $this->createIndex('idx-decrease-created_by', '{{%decrease}}', 'created_by');
        $this->addForeignKey(
            'fk-decrease-created_by',
            '{{%decrease}}',
            'created_by',
            '{{%user}}',
            'id',
            'NO ACTION'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%decrease}}');
    }
}
