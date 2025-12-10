<?php

use yii\db\Migration;

class m251210_171305_add_column_accepted_dist_center_to_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%order}}', 'accepted_dist_center',
            $this->decimal(8,1)->null()->after('date_close')->comment('Принято ТЦ'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%order}}', 'accepted_dist_center');
    }
}
