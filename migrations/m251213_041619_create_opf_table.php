<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%opf}}`.
 */
class m251213_041619_create_opf_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%opf}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(20)->notNull()->unique()->comment('Название'),
            'comment' => $this->text()->null()->defaultValue(null)->comment('Комментарий'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%opf}}');
    }
}
