<?php

use yii\db\Migration;

class m251210_222306_alter_column_quantity_in_delivery_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('delivery_item', 'quantity',
            $this->decimal(8,1)->notNull()->comment('Количество'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('delivery_item', 'quantity',
            $this->integer()->notNull()->comment('Количество'));
    }
}
