<?php

use yii\db\Migration;

class m251216_005931_add_delivery_id_column_in_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%order}}', 'delivery_id',
            $this->integer()->null()->after('type_id')->comment('Поставка'));

        $this->createIndex('idx-order-delivery_id', '{{%order}}', 'delivery_id');
        $this->addForeignKey(
            'fk-order-delivery_id',
            '{{%order}}',
            'delivery_id',
            '{{%delivery}}',
            'id',
            'NO ACTION'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-order-delivery_id', '{{%order}}');
        $this->dropIndex('idx-order-delivery_id', '{{%order}}');
        $this->dropColumn('{{%order}}', 'delivery_id');
    }
}
