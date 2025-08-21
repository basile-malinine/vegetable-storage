<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%employee}}`.
 */
class m250815_203836_create_employee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%employee}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'surname' => $this->string(20)->notNull()->comment('Фамилия'),
            'name' => $this->string(20)->notNull()->comment('Имя'),
            'last_name' => $this->string(20)->null()->comment('Отчество'),
            'phone' => $this->string(10)->null()->unique()->comment('Телефон'),
            'email' => $this->string(50)->null()->unique()->comment('Адрес электронной почты'),
            'created_by' => $this->integer()->notNull()->comment('Создатель'),
            'created_at' => $this->timestamp()->notNull()->comment('Время создания'),
            'updated_at' => $this->timestamp()->notNull()->comment('Время обновления'),
            'comment' => $this->text()->null()->comment('Комментарий'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%employee}}');
    }
}
