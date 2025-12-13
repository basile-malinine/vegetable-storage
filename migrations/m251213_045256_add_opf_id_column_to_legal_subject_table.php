<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%legal_subject}}`.
 */
class m251213_045256_add_opf_id_column_to_legal_subject_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('legal_subject', 'opf_id',
            $this->integer()->notNull()->after('country_id')->comment('ОПФ'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('legal_subject', 'opf_id');
    }
}
