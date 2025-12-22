<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%order_item}}`.
 */
class m251222_005913_add_shipped_column_to_order_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%order_item}}', 'shipped',
            $this->decimal(8, 1)->null()->after('price')->comment('Отгружено'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%order_item}}', 'shipped');
    }
}
