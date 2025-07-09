<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%unit}}`.
 */
class m250702_155641_create_unit_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%unit}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'name' => $this->string(100)->notNull()->unique()->comment('Название'),
            'is_weight' => $this->smallInteger(1)->notNull()->defaultValue(0)->comment('Весовая'),
            'weight' => $this->decimal(10,3)->null()->comment('Вес'),
        ]);

        $this->insert('{{%unit}}', [
            'name' => 'кг',
            'is_weight' => 1,
            'weight' => 1.0,
        ]);

        $this->insert('{{%unit}}', [
            'name' => 'шт',
            'is_weight' => 0,
            'weight' => null,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%unit}}');
    }
}
