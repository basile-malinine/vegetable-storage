<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%moving_item}}`.
 */
class m251225_051550_create_moving_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%moving_item}}', [
            'moving_id' => $this->integer()->notNull()->comment('Перемещение'),
            'assortment_id' => $this->integer()->notNull()->comment('Номенклатура'),
            'pallet_type_id' => $this->integer()->null()->comment('Тип паллета'),
            'quantity' => $this->decimal(8, 1)->notNull()->comment('Количество'),
            'quantity_pallet' => $this->integer()->null()->comment('Количество паллет'),
            'quantity_paks' => $this->integer()->null()->comment('Количество тары'),
            'comment' => $this->text()->null()->comment('Комментарий'),
            'PRIMARY KEY(moving_id, assortment_id)',
        ]);
        $this->addCommentOnTable('moving_item', 'Позиции в Перемещении');

        // Перемещение ---------------------------------------------------------
        $this->createIndex('idx-moving_item-moving_id', '{{%moving_item}}', 'moving_id');
        $this->addForeignKey(
            'fk-moving_item-moving_id',
            '{{%moving_item}}',
            'moving_id',
            '{{%moving}}',
            'id',
            'CASCADE'
        );

        // Номенклатура --------------------------------------------------------
        $this->createIndex('idx-moving_item-assortment_id', '{{%moving_item}}', 'assortment_id');
        $this->addForeignKey(
            'fk-moving_item-assortment_id',
            '{{%moving_item}}',
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
        $this->dropTable('{{%moving_item}}');
    }
}
