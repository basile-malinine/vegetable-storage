<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pallet_type}}`.
 */
class m251130_234739_create_pallet_type_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%pallet_type}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(30)->notNull()->unique()->comment('Название'),
            'comment' => $this->text()->null()->defaultValue(null)->comment('Комментарий'),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%pallet_type}}');
    }
}
