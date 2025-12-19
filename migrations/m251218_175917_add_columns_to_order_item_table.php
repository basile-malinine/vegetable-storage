<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%order_item}}`.
 */
class m251218_175917_add_columns_to_order_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%order_item}}', 'accepted_dist_center',
            $this->decimal(8,1)->null()->after('price')->comment('Принято ТЦ'));
        $this->addColumn('{{%order_item}}', 'comment',
            $this->text()->null()->after('accepted_dist_center')->comment('Комментарий'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%order_item}}', 'accepted_dist_center');
        $this->dropColumn('{{%order_item}}', 'comment');
    }
}
