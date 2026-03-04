<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%acceptance}}`.
 */
class m260303_170558_add_acceptance_remainder_id_column_to_acceptance_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('acceptance', 'acceptance_remainder_id',
            $this->integer()->null()->after('id')->comment('Приёмка на остатке'));
        $this->createIndex('idx-acceptance-acceptance_remainder_id', 'acceptance', 'acceptance_remainder_id');
        $this->addForeignKey(
            'fk-acceptance-acceptance_remainder_id',
            'acceptance',
            'acceptance_remainder_id',
            'acceptance',
            'id',
            'NO ACTION'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-acceptance-acceptance_remainder_id', 'acceptance');
        $this->dropIndex('idx-acceptance-acceptance_remainder_id', 'acceptance');
        $this->dropColumn('acceptance', 'acceptance_remainder_id');
    }
}
