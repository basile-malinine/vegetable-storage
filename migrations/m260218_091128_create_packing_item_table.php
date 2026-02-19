<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%packing_item}}`.
 */
class m260218_091128_create_packing_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%packing_item}}', [
            'packing_id' => $this->integer()->notNull()->comment('Фасовка'),
            'acceptance_id' => $this->integer()->notNull()->comment('Приёмка'),
            'shipment_id' => $this->integer()->null()->comment('Отгрузка'),
            'quantity' => $this->decimal(8, 1)->notNull()->comment('Количество'),
            'quantity_pallet' => $this->integer()->null()->comment('Количество паллет'),
            'quantity_paks' => $this->integer()->null()->comment('Количество тары'),
            'comment' => $this->text()->null()->comment('Комментарий'),
            'PRIMARY KEY(packing_id, acceptance_id)',
        ]);
        $this->addCommentOnTable('packing_item', 'Позиция для Фасовки');

        $this->createIndex('idx-packing_item-acceptance_id', '{{%packing_item}}', 'acceptance_id');
        $this->addForeignKey(
            'fk-packing_item-acceptance_id',
            '{{%packing_item}}',
            'acceptance_id',
            '{{%acceptance}}',
            'id',
            'CASCADE'
        );

        $this->createIndex('idx-packing_item-packing_id', '{{%packing_item}}', 'packing_id');
        $this->addForeignKey(
            'fk-packing_item-packing_id',
            '{{%packing_item}}',
            'packing_id',
            '{{%packing}}',
            'id',
            'CASCADE'
        );

        $this->createIndex('idx-packing_item-shipment_id', '{{%packing_item}}', 'shipment_id');
        $this->addForeignKey(
            'fk-packing_item-shipment_id',
            '{{%packing_item}}',
            'shipment_id',
            '{{%shipment}}',
            'id',
            'NO ACTION'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%packing_item}}');
    }
}
