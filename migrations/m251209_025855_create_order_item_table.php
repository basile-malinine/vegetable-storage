<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%order_item}}`.
 */
class m251209_025855_create_order_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%order_item}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->comment('Заказ'),
            'assortment_id' => $this->integer()->notNull()->comment('Номенклатурная позиция'),
            'quantity' => $this->integer()->notNull()->comment('Количество'),
            'price' => $this->decimal(8, 2)->notNull()->comment('Цена'),
        ]);

        // Заказ ---------------------------------------------------------------
        $this->createIndex('{{%idx-order_item-order_id}}', '{{%order_item}}', 'order_id');
        $this->addForeignKey(
            '{{%fk-order_item-order_id}}',
            '{{%order_item}}',
            'order_id',
            '{{%order}}',
            'id',
            'CASCADE'
        );

        // Номенклатурная позиция ----------------------------------------------
        $this->createIndex('{{%idx-order_item-assortment_id}}', '{{%order_item}}', 'assortment_id');
        $this->addForeignKey(
            '{{%fk-order_item-assortment_id}}',
            '{{%order_item}}',
            'assortment_id',
            '{{%assortment}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-order_item-assortment_id}}', '{{%order_item}}');
        $this->dropIndex('{{%idx-order_item-assortment_id}}', '{{%order_item}}');

        $this->dropForeignKey('{{%fk-order_item-order_id}}', '{{%order_item}}');
        $this->dropIndex('{{%idx-order_item-order_id}}', '{{%order_item}}');

        $this->dropTable('{{%order_item}}');
    }
}
