<?php

use yii\db\Migration;

/**
 * Class m241210_074641_create_table_user
 */
class m241210_074641_create_table_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->comment('Имя пользователя'),
            'email' => $this->string()->notNull()->unique()->comment('E-mail'),
            'pass_hash' => $this->string()->notNull()->comment('Пароль'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('user');
    }
}
