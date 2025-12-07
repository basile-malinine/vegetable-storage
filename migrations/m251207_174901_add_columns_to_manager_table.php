<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%manager}}`.
 */
class m251207_174901_add_columns_to_manager_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%manager}}', 'is_purchasing_mng',
            $this->boolean()->notNull()->defaultValue(0)->after('name')->comment('Менеджер по закупкам'));
        $this->addColumn('{{%manager}}', 'is_sales_mng',
            $this->boolean()->notNull()->defaultValue(0)->after('is_purchasing_mng')->comment('Менеджер по реализации'));
        $this->addColumn('{{%manager}}', 'is_support',
            $this->boolean()->notNull()->defaultValue(0)->after('is_sales_mng')->comment('Отдел сопровождения'));
        $this->addColumn('{{%manager}}', 'is_purchasing_agent',
            $this->boolean()->notNull()->defaultValue(0)->after('is_support')->comment('Агент по закупкам'));
        $this->addColumn('{{%manager}}', 'is_sales_agent',
            $this->boolean()->notNull()->defaultValue(0)->after('is_purchasing_agent')->comment('Агент по реализации'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%manager}}', 'is_sales_agent');
        $this->dropColumn('{{%manager}}', 'is_purchasing_agent');
        $this->dropColumn('{{%manager}}', 'is_support');
        $this->dropColumn('{{%manager}}', 'is_sales_mng');
        $this->dropColumn('{{%manager}}', 'is_purchasing_mng');
    }
}
