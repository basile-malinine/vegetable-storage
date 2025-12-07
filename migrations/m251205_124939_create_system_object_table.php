<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%system_object}}`.
 */
class m251205_124939_create_system_object_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%system_object}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull()->unique()->comment('Название'),
            'table_name' => $this->string(50)->notNull()->unique()->comment('Таблица в БД'),
            'is_google' => $this->smallInteger(1)->notNull()->defaultValue(1)->comment('Поддержка Google'),
            'comment' => $this->text()->null()->comment('Комментарий'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%system_object}}');
    }
}
