<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%legal_subject}}`.
 */
class m251213_172355_add_is_not_nds_column_to_legal_subject_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('legal_subject', 'is_not_nds',
            $this->boolean()->notNull()->defaultValue(0)->after('is_buyer')->comment('Без НДС')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('legal_subject', 'is_not_nds');
    }
}
