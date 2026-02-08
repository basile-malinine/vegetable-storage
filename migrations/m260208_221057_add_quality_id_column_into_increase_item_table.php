<?php

use yii\db\Migration;

class m260208_221057_add_quality_id_column_into_increase_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('increase_item', 'quality_id',
            $this->integer()->null()->after('assortment_id')->comment('Качество'));

        $this->createIndex('idx-increase_item-quality_id', 'increase_item', 'quality_id');
        $this->addForeignKey(
            'fk-increase_item-quality_id',
            'increase_item',
            'quality_id',
            'quality',
            'id',
            'NO ACTION'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-increase_item-quality_id', 'increase_item');
        $this->dropIndex('idx-increase_item-quality_id', 'increase_item');
        $this->dropColumn('increase_item', 'quality_id');
    }
}
