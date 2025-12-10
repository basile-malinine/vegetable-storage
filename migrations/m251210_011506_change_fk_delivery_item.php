<?php

use yii\db\Migration;

class m251210_011506_change_fk_delivery_item extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('{{%fk-delivery_item-assortment_id}}', '{{%delivery_item}}');
        $this->dropForeignKey('{{%fk-delivery_item-delivery_id}}', '{{%delivery_item}}');

        $this->addForeignKey(
            '{{%fk-delivery_item-delivery_id}}',
            '{{%delivery_item}}',
            'delivery_id',
            '{{%delivery}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%fk-delivery_item-assortment_id}}',
            '{{%delivery_item}}',
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
        $this->dropForeignKey('{{%fk-delivery_item-assortment_id}}', '{{%delivery_item}}');
        $this->dropForeignKey('{{%fk-delivery_item-delivery_id}}', '{{%delivery_item}}');

        $this->addForeignKey(
            '{{%fk-delivery_item-delivery_id}}',
            '{{%delivery_item}}',
            'delivery_id',
            '{{%delivery}}',
            'id',
            'NO ACTION',
            'NO ACTION'
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
}
