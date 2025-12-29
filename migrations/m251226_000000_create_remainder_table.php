<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%remainder}}`.
 */
class m251226_000000_create_remainder_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%remainder}}', [
            'acceptance_id' => $this->integer()->comment('Приёмка'),
            'company_own_id' => $this->integer()->comment('Предприятие'),
            'stock_id' => $this->integer()->comment('Склад'),
            'assortment_id' => $this->integer()->comment('Номенклатура'),
            'pallet_type_id' => $this->integer()->null()->comment('Тип паллета'),
            'quantity' => $this->decimal(8, 1)->notNull()->comment('Количество'),
            'quantity_pallet' => $this->integer()->null()->comment('Количество паллет'),
            'quantity_paks' => $this->integer()->null()->comment('Количество тары'),
            'comment' => $this->text()->null()->comment('Комментарий'),
            'created_by' => $this->integer()->comment('Создатель'),
            'created_at' => $this->timestamp()->comment('Дата создания'),
            'updated_at' => $this->timestamp()->comment('Дата обновления'),
            'PRIMARY KEY (acceptance_id)',
        ]);
        $this->addCommentOnTable('remainder', 'Остатки');

        // Приёмка -------------------------------------------------------------
        $this->createIndex('idx-remainder-acceptance_id', '{{%remainder}}', 'acceptance_id');
        $this->addForeignKey(
            'fk-remainder-acceptance_id',
            '{{%remainder}}',
            'acceptance_id',
            '{{%acceptance}}',
            'id',
            'NO ACTION'
        );

        // Предприятие ---------------------------------------------------------
        $this->createIndex('idx-remainder-company_own_id', '{{%remainder}}', 'company_own_id');
        $this->addForeignKey(
            'fk-remainder-company_own_id',
            '{{%remainder}}',
            'company_own_id',
            '{{%legal_subject}}',
            'id',
            'NO ACTION'
        );

        // Склад ---------------------------------------------------------------
        $this->createIndex('idx-remainder-stock_id', '{{%remainder}}', 'stock_id');
        $this->addForeignKey(
            'fk-remainder-stock_id',
            '{{%remainder}}',
            'stock_id',
            '{{%stock}}',
            'id',
            'NO ACTION'
        );

        // Номенклатура --------------------------------------------------------
        $this->createIndex('idx-remainder-assortment_id', '{{%remainder}}', 'assortment_id');
        $this->addForeignKey(
            'fk-remainder-assortment_id',
            '{{%remainder}}',
            'assortment_id',
            '{{%assortment}}',
            'id',
            'NO ACTION'
        );

        // Тип паллет ----------------------------------------------------------
        $this->createIndex('idx-remainder-pallet_type_id', '{{%remainder}}', 'pallet_type_id');
        $this->addForeignKey(
            'fk-remainder-pallet_type_id',
            '{{%remainder}}',
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
        $this->dropTable('{{%remainder}}');
    }
}
