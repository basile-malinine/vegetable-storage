<?php

use yii\db\Migration;

/**
 * Handles dropping columns from table `{{%order}}`.
 */
class m251218_185602_drop_accepted_dist_center_column_from_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%order}}', 'accepted_dist_center');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%order}}', 'accepted_dist_center',
            $this->decimal(8,1)->null()->after('date_close')->comment('Принято ТЦ'));
    }
}
