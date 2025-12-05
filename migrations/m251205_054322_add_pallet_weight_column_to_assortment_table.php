<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%assortment}}`.
 */
class m251205_054322_add_pallet_weight_column_to_assortment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%assortment}}', 'pallet_weight',
            $this->integer()->null()->defaultValue(null)->after('weight')
                ->comment('Средний вес паллета'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%assortment}}', 'pallet_weight');
    }
}
