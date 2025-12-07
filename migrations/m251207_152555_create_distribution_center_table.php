<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%distribution_center}}`.
 */
class m251207_152555_create_distribution_center_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%distribution_center}}', [
            'id' => $this->primaryKey(),
            'legal_subject_id' => $this->integer()->notNull()->comment('Владелец'),
            'name' => $this->string(50)->notNull()->comment('Название'),
            'comment' => $this->text()->null()->comment('Комментарий'),
        ]);

        $this->createIndex(
            'idx-distribution_center-legal_subject_id',
            '{{%distribution_center}}',
            'legal_subject_id'
        );

        $this->addForeignKey(
            'fk-distribution_center-legal_subject_id',
            '{{%distribution_center}}',
            'legal_subject_id',
            '{{%legal_subject}}',
            'id',
            'NO ACTION'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-distribution_center-legal_subject_id', '{{%distribution_center}}');
        $this->dropIndex('idx-distribution_center-legal_subject_id', '{{%distribution_center}}');
        $this->dropTable('{{%distribution_center}}');
    }
}
