<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%manager}}`.
 */
class m251029_230544_create_manager_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%manager}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(30)->notNull()->unique()->comment('Имя'),
            'comment' => $this->text()->null()->comment('Комментарий'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%manager}}');
    }
}
