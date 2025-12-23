<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%refund_item}}`.
 */
class m251222_151141_create_refund_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%refund_item}}', [
            'refund_id' => $this->integer()->notNull()->comment('Возврат'),
            'assortment_id' => $this->integer()->notNull()->comment('Номенклатура'),
            'quantity' => $this->decimal(8, 1)->notNull()->comment('Количество'),
            'comment' => $this->text()->null()->comment('Комментарий'),
            'PRIMARY KEY (refund_id, assortment_id)',
        ]);

        // Заказ ---------------------------------------------------------------
        $this->createIndex('idx-refund_item-refund_id', '{{%refund_item}}', 'refund_id');
        $this->addForeignKey(
            'fk-refund_item-refund_id',
            '{{%refund_item}}',
            'refund_id',
            '{{%refund}}',
            'id',
            'CASCADE'
        );

        // Номенклатура --------------------------------------------------------
        $this->createIndex('idx-refund_item-assortment_id', '{{%refund_item}}', 'assortment_id');
        $this->addForeignKey(
            'fk-refund_item-assortment_id',
            '{{%refund_item}}',
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
        $this->dropTable('{{%refund_item}}');
    }
}
