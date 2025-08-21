<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%acceptance_type}}`.
 */
class m250815_205358_create_acceptance_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%acceptance_type}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'name' => $this->string(30)->notNull()->unique()->comment('Название'),
            'comment' => $this->text()->null()->comment('Комментарий'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%acceptance_type}}');
    }
}
