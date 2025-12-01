<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%car_body}}`.
 */
class m251201_015719_create_car_body_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%car_body}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(30)->notNull()->unique()->comment('Название'),
            'comment' => $this->text()->null()->defaultValue(null)->comment('Комментарий'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%car_body}}');
    }
}
