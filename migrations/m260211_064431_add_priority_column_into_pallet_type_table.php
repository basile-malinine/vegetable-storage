<?php

use yii\db\Migration;

class m260211_064431_add_priority_column_into_pallet_type_table extends Migration
{
    public function up()
    {
        $this->addColumn('pallet_type', 'priority',
            $this->integer()->null()->after('id')->comment('Приоритет'));
    }

    public function down()
    {
        $this->dropColumn('pallet_type', 'priority');
    }
}
