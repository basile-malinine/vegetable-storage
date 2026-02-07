<?php

use yii\db\Migration;

class m260207_001145_rename_column_class_date_to_date extends Migration
{
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->renameColumn('acceptance', 'acceptance_date', 'date');
        $this->renameColumn('moving', 'moving_date', 'date');
        $this->renameColumn('refund', 'refund_date', 'date');
        $this->renameColumn('shipment', 'shipment_date', 'date');
    }

    public function down()
    {
        $this->renameColumn('acceptance', 'date', 'acceptance_date');
        $this->renameColumn('moving', 'date', 'moving_date');
        $this->renameColumn('refund', 'date', 'refund_date');
        $this->renameColumn('shipment', 'date', 'shipment_date');
    }
}
