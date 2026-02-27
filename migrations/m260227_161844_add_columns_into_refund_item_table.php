<?php

use yii\db\Migration;

class m260227_161844_add_columns_into_refund_item_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('refund_item', 'quality_id',
            $this->integer()->null()->after('assortment_id')->comment('Качество'));
        $this->createIndex('idx-refund_item-quality_id', 'refund_item', 'quality_id');
        $this->addForeignKey(
            'fk-refund_item-quality_id',
            'refund_item',
            'quality_id',
            'quality',
            'id',
            'NO ACTION'
        );

        $this->addColumn('refund_item', 'pallet_type_id',
            $this->integer()->null()->after('quality_id')->comment('Тип паллета'));
        $this->createIndex('idx-refund_item-pallet_type_id', 'refund_item', 'pallet_type_id');
        $this->addForeignKey(
            'fk-refund_item-pallet_type_id',
            'refund_item',
            'pallet_type_id',
            'pallet_type',
            'id',
            'NO ACTION'
        );

        $this->addColumn('refund_item', 'quantity_pallet',
            $this->integer()->null()->after('quantity')->comment('Количество паллет'));

        $this->addColumn('refund_item', 'quantity_paks',
            $this->integer()->null()->after('quantity_pallet')->comment('Количество тары'));
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-refund_item-pallet_type_id', 'refund_item');
        $this->dropIndex('idx-refund_item-pallet_type_id', 'refund_item');
        $this->dropColumn('refund_item', 'pallet_type_id');

        $this->dropForeignKey('fk-refund_item-quality_id', 'refund_item');
        $this->dropIndex('idx-refund_item-quality_id', 'refund_item');
        $this->dropColumn('refund_item', 'quality_id');

        $this->dropColumn('refund_item', 'quantity_paks');
        $this->dropColumn('refund_item', 'quantity_pallet');
    }
}
