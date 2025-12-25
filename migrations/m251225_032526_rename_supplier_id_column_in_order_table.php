<?php

use yii\db\Migration;

class m251225_032526_rename_supplier_id_column_in_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('order', 'supplier_id', 'company_own_id');
        $this->addCommentOnColumn('order', 'company_own_id', 'Предприятие');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('order', 'company_own_id', 'supplier_id');
        $this->addCommentOnColumn('order', 'supplier_id', 'Поставщик');
    }
}
