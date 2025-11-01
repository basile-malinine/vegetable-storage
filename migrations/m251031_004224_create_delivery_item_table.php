<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%delivery_item}}`.
 */
class m251031_004224_create_delivery_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%delivery_item}}', [
            'id' => $this->primaryKey(),
            'delivery_id' => $this->integer()->comment('Доставка'),
            'assortment_id' => $this->integer()->notNull()->comment('Номенклатурная позиция'),
            'quantity' => $this->integer()->notNull()->comment('Количество'),
            'price' => $this->decimal(8, 2)->notNull()->comment('Цена'),
        ]);

        $this->createIndex(
            '{{%idx-delivery_item-delivery_id}}',
            '{{%delivery_item}}',
            'delivery_id'
        );
        $this->addForeignKey(
            '{{%fk-delivery_item-delivery_id}}',
            '{{%delivery_item}}',
            'delivery_id',
            '{{%delivery}}',
            'id',
            'NO ACTION',
            'NO ACTION'
        );

        $this->createIndex(
            '{{%idx-delivery_item-assortment_id}}',
            '{{%delivery_item}}',
            'assortment_id'
        );
        $this->addForeignKey(
            '{{%fk-delivery_item-assortment_id}}',
            '{{%delivery_item}}',
            'assortment_id',
            '{{%assortment}}',
            'id',
            'NO ACTION',
            'NO ACTION'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-delivery_item-assortment_id}}', '{{%delivery_item}}');
        $this->dropIndex('{{%idx-delivery_item-assortment_id}}', '{{%delivery_item}}');

        $this->dropForeignKey('{{%fk-delivery_item-delivery_id}}', '{{%delivery_item}}');
        $this->dropIndex('{{%idx-delivery_item-delivery_id}}', '{{%delivery_item}}');

        $this->dropTable('{{%delivery_item}}');
    }
}
