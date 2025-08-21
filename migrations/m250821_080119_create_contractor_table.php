<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%contractor}}`.
 */
class m250821_080119_create_contractor_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%contractor}}', [
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
        $this->dropTable('{{%contractor}}');
    }
}
