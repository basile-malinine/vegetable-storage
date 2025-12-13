<?php

use yii\db\Migration;

class m251213_060301_add_fk_opf_id_to_legal_subject_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('idx-legal_subject-opf_id', 'legal_subject', 'opf_id');
        $this->addForeignKey(
            'fk-legal_subject-opf_id',
            'legal_subject',
            'opf_id',
            'opf',
            'id',
            'NO ACTION'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-legal_subject-opf_id', 'legal_subject');
        $this->dropIndex('idx-legal_subject-opf_id', 'legal_subject');
    }
}
