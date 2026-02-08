<?php

use yii\db\Migration;

class m260208_221525_add_quality_id_column_into_moving_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('moving_item', 'quality_id',
            $this->integer()->null()->after('assortment_id')->comment('Качество'));

        $this->createIndex('idx-moving_item-quality_id', 'moving_item', 'quality_id');
        $this->addForeignKey(
            'fk-moving_item-quality_id',
            'moving_item',
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
        $this->dropForeignKey('fk-moving_item-quality_id', 'moving_item');
        $this->dropIndex('idx-moving_item-quality_id', 'moving_item');
        $this->dropColumn('moving_item', 'quality_id');
    }
}
