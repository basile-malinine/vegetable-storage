<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%merging}}`.
 */
class m260211_064440_create_merging_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%merging}}', [
            'id' => $this->primaryKey(),
            'company_own_id' => $this->integer()->notNull()->comment('Предприятие'),
            'stock_id' => $this->integer()->notNull()->comment('Склад'),
            'assortment_id' => $this->integer()->notNull()->comment('Номенклатура'),
            'date' => $this->timestamp()->notNull()->comment('Дата объединения'),
            'date_close' => $this->timestamp()->null()->comment('Дата закрытия'),
            'comment' => $this->text()->null()->comment('Комментарий'),
            'created_by' => $this->integer()->comment('Создатель'),
            'created_at' => $this->timestamp()->comment('Дата создания'),
            'updated_at' => $this->timestamp()->comment('Дата обновления'),
        ]);
        $this->addCommentOnTable('merging', 'Объединение');

        $this->createIndex('idx-merging-company_own_id', 'merging', 'company_own_id');
        $this->addForeignKey(
            'fk-merging-company_own_id',
            'merging',
            'company_own_id',
            'legal_subject',
            'id',
            'NO ACTION');

        $this->createIndex('idx-merging-stock_id', 'merging', 'stock_id');
        $this->addForeignKey(
            'fk-merging-stock_id',
            'merging',
            'stock_id',
            'stock',
            'id',
            'NO ACTION');

        $this->createIndex('idx-merging-assortment_id', 'merging', 'assortment_id');
        $this->addForeignKey(
            'fk-merging-assortment_id',
            'merging',
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
        $this->dropTable('{{%merging}}');
    }
}
