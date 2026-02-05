<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%increase_item}}`.
 */
class m260205_073926_create_increase_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%increase_item}}', [
            'increase_id' => $this->integer()->notNull()->comment('Оприходование'),
            'assortment_id' => $this->integer()->notNull()->comment('Номенклатура'),
            'pallet_type_id' => $this->integer()->null()->comment('Тип паллета'),
            'quantity' => $this->decimal(8, 1)->notNull()->comment('Количество'),
            'quantity_pallet' => $this->integer()->null()->comment('Количество паллет'),
            'quantity_paks' => $this->integer()->null()->comment('Количество тары'),
            'comment' => $this->text()->null()->comment('Комментарий'),
            'PRIMARY KEY(increase_id, assortment_id)',
        ]);
        $this->addCommentOnTable('increase_item', 'Позиции в Оприходовании');

        // Оприходование -------------------------------------------------------
        $this->createIndex('idx-increase_item-increase_id', '{{%increase_item}}', 'increase_id');
        $this->addForeignKey(
            'fk-increase_item-increase_id',
            '{{%increase_item}}',
            'increase_id',
            '{{%increase}}',
            'id',
            'CASCADE'
        );

        // Номенклатура --------------------------------------------------------
        $this->createIndex('idx-increase_item-assortment_id', '{{%increase_item}}', 'assortment_id');
        $this->addForeignKey(
            'fk-increase_item-assortment_id',
            '{{%increase_item}}',
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
        $this->dropTable('{{%increase_item}}');
    }
}
