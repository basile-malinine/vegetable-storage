<?php

use yii\db\Migration;

class m251216_000630_restructuring_delivery_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('{{%delivery_item}}');

        $this->createTable('{{%delivery_item}}', [
            'delivery_id' => $this->integer()->notNull()->comment('Поставка'),
            'assortment_id' => $this->integer()->notNull()->comment('Номенклатура'),
            'shipped' => $this->decimal(8, 1)->notNull()->comment('Отправлено'),
            'price' => $this->decimal(8,2)->notNull()->comment('Цена'),
            'unloading_type_id' => $this->integer()->notNull()->comment('Тип выгрузки'),
            'quality_id' => $this->integer()->null()->comment('Качество'),
            'cost_before_stock' => $this->decimal(8, 2)->null()
                ->comment('Себестоимость до склада'),
            'price_total' => $this->decimal(8, 2)->notNull()
                ->comment('Общая стоимость в поставке'),
            'profit_expected' => $this->decimal(8, 2)->null()
                ->comment('Ожидаемая прибыль'),
            'work_plan' => $this->text()->null()->comment('План по работе'),
            'PRIMARY KEY(delivery_id, assortment_id)',
        ]);

        $this->createIndex('idx-delivery_item-delivery_id', '{{%delivery_item}}', 'delivery_id');
        $this->addForeignKey(
            'fk-delivery_item-delivery_id',
            '{{%delivery_item}}',
            'delivery_id',
            '{{%delivery}}',
            'id',
            'CASCADE'
        );

        $this->createIndex('idx-delivery_item-assortment_id', '{{%delivery_item}}', 'assortment_id');
        $this->addForeignKey(
            'fk-delivery_item-assortment_id',
            '{{%delivery_item}}',
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
        $this->dropTable('{{%delivery_item}}');

        $this->createTable('{{%delivery_item}}', [
            'id' => $this->primaryKey(),
            'delivery_id' => $this->integer()->notNull()->comment('Поставка'),
            'assortment_id' => $this->integer()->notNull()->comment('Номенклатура'),
            'shipped' => $this->decimal(8, 1)->notNull()->comment('Отправлено'),
            'price' => $this->decimal(8,2)->notNull()->comment('Цена'),
            'unloading_type_id' => $this->integer()->notNull()->comment('Тип выгрузки'),
            'quality_id' => $this->integer()->null()->comment('Качество'),
            'cost_before_stock' => $this->decimal(8, 2)->null()
                ->comment('Себестоимость до склада'),
            'price_total' => $this->decimal(8, 2)->notNull()
                ->comment('Общая стоимость в поставке'),
            'profit_expected' => $this->decimal(8, 2)->null()
                ->comment('Ожидаемая прибыль'),
            'work_plan' => $this->text()->null()->comment('План по работе'),
        ]);

        // Поставка ------------------------------------------------------------
        $this->createIndex('idx-delivery_item-delivery_id', '{{%delivery_item}}', 'delivery_id');
        $this->addForeignKey(
            'fk-delivery_item-delivery_id',
            '{{%delivery_item}}',
            'delivery_id',
            '{{%delivery}}',
            'id',
            'CASCADE'
        );

        // Номенклатурная позиция ----------------------------------------------
        $this->createIndex('idx-delivery_item-assortment_id', '{{%delivery_item}}', 'assortment_id');
        $this->addForeignKey(
            'fk-delivery_item-assortment_id',
            '{{%delivery_item}}',
            'assortment_id',
            '{{%assortment}}',
            'id',
            'CASCADE'
        );
    }
}
