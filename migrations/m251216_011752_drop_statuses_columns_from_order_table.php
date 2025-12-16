<?php

use yii\db\Migration;

/**
 * Handles dropping columns from table `{{%order}}`.
 */
class m251216_011752_drop_statuses_columns_from_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%order}}', 'status_main_id');
        $this->dropColumn('{{%order}}', 'status_additional_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%order}}', 'status_main_id',
            $this->integer()->null()->after('sales_agent_id')->comment('Статус учёта'));
        $this->addColumn('{{%order}}', 'status_main_id',
            $this->integer()->null()->after('status_main_id')->comment('Статус реализации'));
    }
}
