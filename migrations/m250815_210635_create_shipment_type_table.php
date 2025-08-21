<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%shipment_type}}`.
 */
class m250815_210635_create_shipment_type_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%shipment_type}}', [
            'id' => $this->primaryKey()->comment('ID'),
            'name' => $this->string(30)->notNull()->unique()->comment('Название'),
            'comment' => $this->text()->null()->comment('Комментарий'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%shipment_type}}');
    }
}
