<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%assortment}}`.
 */
class m250706_074513_create_assortment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%assortment}}', [
            'id' => $this->primaryKey(),
            'unit_id' => $this->integer()->notNull()->comment('Единица измерения'),
            'product_id' => $this->integer()->null()->comment('Продукт'),
            'name' => $this->string(100)->notNull()->unique()->comment('Название'),
            'weight' => $this->decimal(10, 3)->notNull()->comment('Вес'),
            'comment' => $this->text()->null()->comment('Комментарий'),
            'created_by' => $this->integer()->notNull()->comment('Создатель'),
            'created_at' => $this->timestamp()->notNull()->comment('Дата создания'),
            'updated_at' => $this->timestamp()->notNull()->comment('Дата обновления'),
        ]);

        $this->createIndex(
            '{{%idx-assortment-unit_id}}',
            '{{%assortment}}',
            'unit_id'
        );

        $this->addForeignKey(
            '{{%fk-assortment-unit_id}}',
            '{{%assortment}}',
            'unit_id',
            '{{%unit}}',
            'id',
            'NO ACTION'
        );

        $this->createIndex(
            '{{%idx-assortment-product_id}}',
            '{{%assortment}}',
            'product_id'
        );

        $this->addForeignKey(
            '{{%fk-assortment-product_id}}',
            '{{%assortment}}',
            'product_id',
            '{{%product}}',
            'id',
            'NO ACTION'
        );

        $this->createIndex(
            '{{%idx-assortment-created_by}}',
            '{{%assortment}}',
            'created_by'
        );

        $this->addForeignKey(
            '{{%fk-assortment-created_by}}',
            '{{%assortment}}',
            'created_by',
            '{{%user}}',
            'id',
            'NO ACTION'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            '{{%fk-assortment-created_by}}',
            '{{%assortment}}',
        );

        $this->dropIndex(
            '{{%idx-assortment-created_by}}',
            '{{%assortment}}',
        );

        $this->dropForeignKey(
            '{{%fk-assortment-product_id}}',
            '{{%assortment}}',
        );

        $this->dropIndex(
            '{{%idx-assortment-product_id}}',
            '{{%assortment}}',
        );

        $this->dropForeignKey(
            '{{%fk-assortment-unit_id}}',
            '{{%assortment}}',
        );

        $this->dropIndex(
            '{{%idx-assortment-unit_id}}',
            '{{%assortment}}',
        );

        $this->dropTable('{{%assortment}}');
    }
}
