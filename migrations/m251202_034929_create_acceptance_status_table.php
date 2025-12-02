<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%acceptance_status}}`.
 */
class m251202_034929_create_acceptance_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%acceptance_status}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull()->unique()->comment('Название'),
            'comment' => $this->text()->null()->defaultValue(null)->comment('Комментарий'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%acceptance_status}}');
    }
}
