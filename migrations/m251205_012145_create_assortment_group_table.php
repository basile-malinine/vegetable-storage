<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%assortment_group}}`.
 */
class m251205_012145_create_assortment_group_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%assortment_group}}', [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer()->null()->comment('Родительская группа'),
            'name' => $this->string(50)->notNull()->comment('Название группы'),
        ]);

        $this->createIndex(
            '{{%idx-assortment_group-parent_id}}',
            '{{%assortment_group}}',
            'parent_id'
        );

        $this->addForeignKey(
            '{{%fk-assortment_group-parent_id}}',
            '{{%assortment_group}}',
            'parent_id',
            '{{%assortment_group}}',
            'id',
            'NO ACTION'
        );

        // Добавляем поле-ссылку для Номенклатуры
        $this->addColumn('{{%assortment}}', 'assortment_group_id',
            $this->integer()->notNull()->comment('Подгруппа классификатора')->after('unit_id'));

        $this->createIndex(
            '{{%idx-assortment-assortment_group_id}}',
            '{{%assortment}}',
            'assortment_group_id'
        );

        $this->addForeignKey(
            '{{%fk-assortment-assortment_group_id}}',
            '{{%assortment}}',
            'assortment_group_id',
            '{{%assortment_group}}',
            'id',
            'NO ACTION'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-assortment-assortment_group_id', '{{%assortment}}');
        $this->dropIndex('{{%idx-assortment-group_id}}', '{{%assortment}}');
        $this->dropColumn('{{%assortment}}', 'assortment_group_id');
        $this->dropForeignKey('{{%fk-assortment_group-parent_id}}', '{{%assortment_group}}');
        $this->dropIndex('{{%idx-assortment_group-parent_id}}', '{{%assortment_group}}');
        $this->dropTable('{{%assortment_group}}');
    }
}
