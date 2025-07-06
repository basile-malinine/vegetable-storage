<?php

use yii\db\Migration;

/**
 * Class m241210_074641_create_user_table
 */
class m241210_074641_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->comment('Имя пользователя'),
            'email' => $this->string()->notNull()->unique()->comment('E-mail'),
            'pass_hash' => $this->string()->notNull()->comment('Пароль'),
        ]);
        $this->insert('{{%user}}', [
            'name' => 'admin',
            'email' => 'admin@mail.net',
            'pass_hash' => Yii::$app->getSecurity()->generatePasswordHash('admin'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
