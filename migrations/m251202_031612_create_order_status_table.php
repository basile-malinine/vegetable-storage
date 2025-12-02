<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%order_status}}`.
 */
class m251202_031612_create_order_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%order_status}}', [
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
        $this->dropTable('{{%order_status}}');
    }
}
