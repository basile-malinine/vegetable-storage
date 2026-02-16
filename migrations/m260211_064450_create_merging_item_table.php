<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%merging_item}}`.
 */
class m260211_064450_create_merging_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%merging_item}}', [
            'merging_id' => $this->integer()->notNull()->comment('Объединение'),
            'acceptance_id' => $this->integer()->notNull()->comment('Приёмка'),
            'quantity' => $this->decimal(8, 1)->notNull()->comment('Количество'),
            'quantity_pallet' => $this->integer()->null()->comment('Количество паллет'),
            'quantity_paks' => $this->integer()->null()->comment('Количество тары'),
            'comment' => $this->text()->null()->comment('Комментарий'),
            'PRIMARY KEY(merging_id, acceptance_id)',
        ]);
        $this->addCommentOnTable('merging_item', 'Позиция для Объединения');

        $this->createIndex('idx-merging_item-acceptance_id', '{{%merging_item}}', 'acceptance_id');
        $this->addForeignKey(
            'fk-merging_item-acceptance_id',
            '{{%merging_item}}',
            'acceptance_id',
            '{{%acceptance}}',
            'id',
            'CASCADE'
        );

        $this->createIndex('idx-merging_item-merging_id', '{{%merging_item}}', 'merging_id');
        $this->addForeignKey(
            'fk-merging_item-merging_id',
            '{{%merging_item}}',
            'merging_id',
            '{{%merging}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%merging_item}}');
    }
}
