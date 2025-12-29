<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%shipment_acceptance}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%shipment}}`
 * - `{{%acceptance}}`
 */
class m251226_110012_create_junction_table_for_shipment_and_acceptance_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%shipment_acceptance}}', [
            'shipment_id' => $this->integer()->comment('Отгрузка'),
            'acceptance_id' => $this->integer()->comment('Приёмка'),
            'pallet_type_id' => $this->integer()->null()->comment('Тип паллета'),
            'quantity' => $this->decimal(8, 1)->notNull()->comment('Количество'),
            'quantity_pallet' => $this->integer()->null()->comment('Количество паллет'),
            'quantity_paks' => $this->integer()->null()->comment('Количество тары'),
            'comment' => $this->text()->null()->comment('Комментарий'),
            'PRIMARY KEY(shipment_id, acceptance_id)',
        ]);
        $this->addCommentOnTable('shipment_acceptance', 'Приёмки в Отгрузках');

        // Отгрузка ------------------------------------------------------------
        $this->createIndex(
            '{{%idx-shipment_acceptance-shipment_id}}',
            '{{%shipment_acceptance}}',
            'shipment_id'
        );
        $this->addForeignKey(
            '{{%fk-shipment_acceptance-shipment_id}}',
            '{{%shipment_acceptance}}',
            'shipment_id',
            '{{%shipment}}',
            'id',
            'CASCADE'
        );

        // Приёмка -------------------------------------------------------------
        $this->createIndex(
            '{{%idx-shipment_acceptance-acceptance_id}}',
            '{{%shipment_acceptance}}',
            'acceptance_id'
        );
        $this->addForeignKey(
            '{{%fk-shipment_acceptance-acceptance_id}}',
            '{{%shipment_acceptance}}',
            'acceptance_id',
            '{{%acceptance}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%shipment_acceptance}}');
    }
}
