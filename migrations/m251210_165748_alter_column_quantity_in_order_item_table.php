<?php

use yii\db\Migration;

class m251210_165748_alter_column_quantity_in_order_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('order_item', 'quantity',
            $this->decimal(8,1)->notNull()->comment('Количество'));

        // В таблице delivery_item тоже...
        $this->alterColumn('delivery_item', 'quantity',
            $this->decimal(8,1)->notNull()->comment('Количество'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('order_item', 'quantity',
            $this->integer()->notNull()->comment('Количество'));

        $this->alterColumn('delivery_item', 'quantity',
            $this->integer()->notNull()->comment('Количество'));
    }
}
