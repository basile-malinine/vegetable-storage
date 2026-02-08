<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%sorting_item}}`.
 */
class m260208_151943_create_sorting_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%sorting_item}}', [
            'sorting_id' => $this->integer()->notNull()->comment('Переработка'),
            'assortment_id' => $this->integer()->notNull()->comment('Номенклатура'),
            'quality_id' => $this->integer()->null()->comment('Качество'),
            'pallet_type_id' => $this->integer()->null()->comment('Тип палет'),
            'quantity' => $this->decimal(8, 1)->notNull()->comment('Количество'),
            'quantity_pallet' => $this->integer()->null()->comment('Количество паллет'),
            'quantity_paks' => $this->integer()->null()->comment('Количество тары'),
            'comment' => $this->text()->null()->comment('Комментарий'),
            'PRIMARY KEY(sorting_id, assortment_id)',
        ]);
        $this->addCommentOnTable('sorting_item', 'Позиции в Переборке');

        // ----------------------------------------------------------- Переборка
        $this->createIndex('idx-sorting_item-sorting_id', '{{%sorting_item}}', 'sorting_id');
        $this->addForeignKey(
            'fk-sorting_item-sorting_id',
            '{{%sorting_item}}',
            'sorting_id',
            '{{%sorting}}',
            'id',
            'CASCADE'
        );

        // ----------------------------------------------------------- Номенклатура
        $this->createIndex('idx-sorting_item-assortment_id', '{{%sorting_item}}', 'assortment_id');
        $this->addForeignKey(
            'fk-sorting_item-assortment_id',
            '{{%sorting_item}}',
            'assortment_id',
            '{{%assortment}}',
            'id',
            'NO ACTION'
        );

        // ----------------------------------------------------------- Качество
        $this->createIndex('idx-sorting_item-quality_id', '{{%sorting_item}}', 'quality_id');
        $this->addForeignKey(
            'fk-sorting_item-quality_id',
            '{{%sorting_item}}',
            'quality_id',
            '{{%quality}}',
            'id',
            'NO ACTION'
        );

        // ----------------------------------------------------------- Тип палет
        $this->createIndex('idx-sorting_item-pallet_type_id', '{{%sorting_item}}', 'pallet_type_id');
        $this->addForeignKey(
            'fk-sorting_item-pallet_type_id',
            '{{%sorting_item}}',
            'pallet_type_id',
            '{{%pallet_type}}',
            'id',
            'NO ACTION'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%sorting_item}}');
    }
}
