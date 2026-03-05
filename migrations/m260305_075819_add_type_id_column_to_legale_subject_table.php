<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%legale_subject}}`.
 */
class m260305_075819_add_type_id_column_to_legale_subject_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('legal_subject', 'type_id',
            $this->integer()->notNull()->after('id')->defaultValue(null));

        $this->update('legal_subject', ['type_id' => 1], ['is_legal' => 1]);
        $this->update('legal_subject', ['type_id' => 2], ['is_legal' => 0]);

        $this->alterColumn('legal_subject', 'type_id',
            $this->integer()->notNull()->comment('Тип контрагента'));
        $this->createIndex('idx-legal_subject-type_id', 'legal_subject', 'type_id');

        $this->dropColumn('legal_subject', 'is_legal');

        $this->alterColumn('legal_subject', 'opf_id',
            $this->integer()->null()->comment('ОПФ'));
    }

    public function safeDown()
    {
        $this->addColumn('legal_subject', 'is_legal',
            $this->smallInteger()->notNull()->defaultValue(1)->after('opf_id')->comment('Юридическое лицо'));

        $this->update('legal_subject', ['is_legal' => 0], ['type_id' => [2, 3]]);

        $this->dropColumn('legal_subject', 'type_id');
    }
}
