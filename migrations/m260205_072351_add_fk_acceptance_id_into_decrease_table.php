<?php

use yii\db\Migration;

class m260205_072351_add_fk_acceptance_id_into_decrease_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Приёмка -------------------------------------------------------------
        $this->createIndex('idx-decrease-acceptance_id', 'decrease', 'acceptance_id');
        $this->addForeignKey(
            'fk-decrease-acceptance_id',
            'decrease',
            'acceptance_id',
            'acceptance',
            'id',
            'NO ACTION'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-decrease-acceptance_id', 'decrease');
        $this->dropIndex('idx-decrease-acceptance_id', 'decrease');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260205_072351_add_fk_acceptance_id_into_decrease_table cannot be reverted.\n";

        return false;
    }
    */
}
