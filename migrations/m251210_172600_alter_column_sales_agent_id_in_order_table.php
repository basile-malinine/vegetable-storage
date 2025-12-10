<?php

use yii\db\Migration;

class m251210_172600_alter_column_sales_agent_id_in_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('order', 'sales_agent_id',
            $this->integer()->null()->comment('Агент по реализации'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('order', 'sales_agent_id',
            $this->integer()->notNull()->comment('Агент по реализации'));
    }
}
