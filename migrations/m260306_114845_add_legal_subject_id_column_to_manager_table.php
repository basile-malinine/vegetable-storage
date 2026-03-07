<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%manager}}`.
 */
class m260306_114845_add_legal_subject_id_column_to_manager_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('manager', 'legal_subject_id',
            $this->integer()->null()->after('id')->comment('ИП / Физ. лицо'));
        $this->createIndex('idx-manager-legal_subject_id', 'manager', 'legal_subject_id');
        $this->addForeignKey(
            'fk-manager-legal_subject_id',
            'manager',
            'legal_subject_id',
            'legal_subject',
            'id',
            'NO ACTION'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-manager-legal_subject_id', 'manager');
        $this->dropIndex('idx-manager-legal_subject_id', 'manager');
        $this->dropColumn('manager', 'legal_subject_id');
    }
}
