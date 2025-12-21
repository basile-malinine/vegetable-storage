<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%acceptance_item}}`.
 */
class m251220_012128_create_acceptance_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%acceptance_item}}', [
            'acceptance_id' => $this->integer()->notNull()->comment('Приёмка'),
            'assortment_id' => $this->integer()->notNull()->comment('Номенклатура'),
            'pallet_type_id' => $this->integer()->null()->comment('Тип паллета'),
            'quantity' => $this->decimal(8, 1)->notNull()->comment('Количество'),
            'quantity_pallet' => $this->integer()->null()->comment('Количество паллет'),
            'quantity_paks' => $this->integer()->null()->comment('Количество тары'),
            'comment' => $this->text()->null()->comment('Комментарий'),
            'PRIMARY KEY(acceptance_id, assortment_id)',
        ]);

        // Приёмка -------------------------------------------------------------
        $this->createIndex('idx-acceptance_item-acceptance_id', '{{%acceptance_item}}', 'acceptance_id');
        $this->addForeignKey(
            'fk-acceptance_item-acceptance_id',
            '{{%acceptance_item}}',
            'acceptance_id',
            '{{%acceptance}}',
            'id',
            'CASCADE'
        );

        // Номенклатура --------------------------------------------------------
        $this->createIndex('idx-acceptance_item-assortment_id', '{{%acceptance_item}}', 'assortment_id');
        $this->addForeignKey(
            'fk-acceptance_item-assortment_id',
            '{{%acceptance_item}}',
            'assortment_id',
            '{{%assortment}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%acceptance_item}}');
    }
}
