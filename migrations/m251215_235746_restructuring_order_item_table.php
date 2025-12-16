<?php

use yii\db\Migration;

class m251215_235746_restructuring_order_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('{{%order_item}}');

        $this->createTable('{{%order_item}}', [
            'order_id' => $this->integer()->comment('Заказ'),
            'assortment_id' => $this->integer()->notNull()->comment('Номенклатурная позиция'),
            'quantity' => $this->integer()->notNull()->comment('Количество'),
            'price' => $this->decimal(8, 2)->notNull()->comment('Цена'),
            'PRIMARY KEY(order_id, assortment_id)',
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
        $this->dropTable('{{%order_item}}');

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
}
