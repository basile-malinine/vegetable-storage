<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%work_type}}`.
 */
class m250821_075722_create_work_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%work_type}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'name' => $this->string(30)->notNull()->comment('Название'),
            'comment' => $this->text()->null()->comment('Комментарий'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%work_type}}');
    }
}
