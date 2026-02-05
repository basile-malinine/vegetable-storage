<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%decrease_item}}`.
 */
class m260204_194354_create_decrease_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%decrease_item}}', [
            'decrease_id' => $this->integer()->notNull()->comment('Списание'),
            'assortment_id' => $this->integer()->notNull()->comment('Номенклатура'),
            'pallet_type_id' => $this->integer()->null()->comment('Тип паллета'),
            'quantity' => $this->decimal(8, 1)->notNull()->comment('Количество'),
            'quantity_pallet' => $this->integer()->null()->comment('Количество паллет'),
            'quantity_paks' => $this->integer()->null()->comment('Количество тары'),
            'comment' => $this->text()->null()->comment('Комментарий'),
            'PRIMARY KEY(decrease_id, assortment_id)',
        ]);
        $this->addCommentOnTable('moving_item', 'Позиции в Списании');

        // Списание ------------------------------------------------------------
        $this->createIndex('idx-decrease_item-decrease_id', '{{%decrease_item}}', 'decrease_id');
        $this->addForeignKey(
            'fk-decrease_item-decrease_id',
            '{{%decrease_item}}',
            'decrease_id',
            '{{%decrease}}',
            'id',
            'CASCADE'
        );

        // Номенклатура --------------------------------------------------------
        $this->createIndex('idx-decrease_item-assortment_id', '{{%decrease_item}}', 'assortment_id');
        $this->addForeignKey(
            'fk-decrease_item-assortment_id',
            '{{%decrease_item}}',
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
        $this->dropTable('{{%decrease_item}}');
    }
}
