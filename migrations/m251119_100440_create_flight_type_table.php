<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%flight_type}}`.
 * Тип рейса
 */
class m251119_100440_create_flight_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%flight_type}}', [
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
        $this->dropTable('{{%flight_type}}');
    }
}
