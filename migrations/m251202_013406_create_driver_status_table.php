<?php
/**
 * Статус Водитель для Google
 */

use yii\db\Migration;

/**
 * Handles the creation of table `{{%driver_status}}`.
 */
class m251202_013406_create_driver_status_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%driver_status}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(30)->notNull()->unique()->comment('Название'),
            'comment' => $this->text()->null()->defaultValue(null)->comment('Комментарий'),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%driver_status}}');
    }
}
