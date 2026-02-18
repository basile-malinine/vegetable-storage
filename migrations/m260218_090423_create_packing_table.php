<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%packing}}`.
 */
class m260218_090423_create_packing_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%packing}}', [
            'id' => $this->primaryKey(),
            'company_own_id' => $this->integer()->notNull()->comment('Предприятие'),
            'stock_id' => $this->integer()->notNull()->comment('Склад'),
            'assortment_id' => $this->integer()->notNull()->comment('Номенклатура'),
            'date' => $this->timestamp()->notNull()->comment('Дата фасовки'),
            'date_close' => $this->timestamp()->null()->comment('Дата закрытия'),
            'comment' => $this->text()->null()->comment('Комментарий'),
            'created_by' => $this->integer()->comment('Создатель'),
            'created_at' => $this->timestamp()->comment('Дата создания'),
            'updated_at' => $this->timestamp()->comment('Дата обновления'),
        ]);
        $this->addCommentOnTable('packing', 'Фасовка');

        $this->createIndex('idx-packing-company_own_id', 'packing', 'company_own_id');
        $this->addForeignKey(
            'fk-packing-company_own_id',
            'packing',
            'company_own_id',
            'legal_subject',
            'id',
            'NO ACTION');

        $this->createIndex('idx-packing-stock_id', 'packing', 'stock_id');
        $this->addForeignKey(
            'fk-packing-stock_id',
            'packing',
            'stock_id',
            'stock',
            'id',
            'NO ACTION');

        $this->createIndex('idx-packing-assortment_id', 'packing', 'assortment_id');
        $this->addForeignKey(
            'fk-packing-assortment_id',
            'packing',
            'assortment_id',
            'assortment',
            'id',
            'NO ACTION');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%packing}}');
    }
}
