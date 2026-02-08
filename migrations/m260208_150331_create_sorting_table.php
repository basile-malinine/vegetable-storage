<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%sorting}}`.
 */
class m260208_150331_create_sorting_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%sorting}}', [
            'id' => $this->primaryKey(),
            'acceptance_id' => $this->integer()->notNull()->comment('Приёмка'),
            'date' => $this->timestamp()->comment('Дата'),
            'date_close' => $this->timestamp()->null()->comment('Дата закрытия'),
            'comment' => $this->text()->null()->comment('Комментарий'),
            'created_by' => $this->integer()->comment('Создатель'),
            'created_at' => $this->timestamp()->comment('Дата создания'),
            'updated_at' => $this->timestamp()->comment('Дата обновления'),
        ]);
        $this->addCommentOnTable('sorting', 'Переработка');

        $this->createIndex('idx-sorting-acceptance_id', 'sorting', 'acceptance_id');
        $this->addForeignKey(
            'fk-sorting-acceptance_id',
            'sorting',
            'acceptance_id',
            'acceptance',
            'id',
            'NO ACTION'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%sorting}}');
    }
}
