<?php

use yii\db\Migration;

class m260208_125451_add_quality_id_column_into_acceptance_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('acceptance_item', 'quality_id',
            $this->integer()->null()->after('assortment_id')->comment('Качество'));

        $this->createIndex('idx-acceptance_item-quality_id', 'acceptance_item', 'quality_id');
        $this->addForeignKey(
            'fk-acceptance_item-quality_id',
            'acceptance_item',
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
        $this->dropForeignKey('fk-acceptance_item-quality_id', 'acceptance_item');
        $this->dropIndex('idx-acceptance_item-quality_id', 'acceptance_item');
        $this->dropColumn('acceptance_item', 'quality_id');
    }
}
