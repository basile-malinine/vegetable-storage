<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%gate_type}}`.
 */
class m251130_215034_create_gate_type_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%gate_type}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(30)->notNull()->unique()->comment('Название'),
            'comment' => $this->text()->null()->defaultValue(null)->comment('Комментарий'),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%gate_type}}');
    }
}
