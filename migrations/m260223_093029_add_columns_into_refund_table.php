<?php

use yii\db\Migration;

class m260223_093029_add_columns_into_refund_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('refund', 'order_company_own_id',
            $this->integer()->notNull()->after('type_id')->comment('Предприятие в заказе'));
        $this->createIndex('idx-refund-order_company_own_id', 'refund', 'order_company_own_id');

        $this->addColumn('refund', 'order_stock_id',
            $this->integer()->null()->after('order_company_own_id')->comment('Склад в заказе'));
        $this->createIndex('idx-refund-order_stock_id', 'refund', 'order_stock_id');

        $this->addColumn('refund', 'order_executor_id',
            $this->integer()->null()->after('order_stock_id')->comment('Исполнитель в заказе'));
        $this->createIndex('idx-refund-order_executor_id', 'refund', 'order_executor_id');

        $this->addColumn('refund', 'status_id',
            $this->integer()->notNull()->after('order_executor_id')->comment('Статус возврата'));
        $this->createIndex('idx-refund-status_id', 'refund', 'status_id');
    }

    public function safeDown()
    {
        $this->dropIndex('idx-refund-status_id', 'refund');
        $this->dropColumn('refund', 'status_id');

        $this->dropIndex('idx-refund-order_executor_id', 'refund');
        $this->dropColumn('refund', 'order_executor_id');

        $this->dropIndex('idx-refund-order_stock_id', 'refund');
        $this->dropColumn('refund', 'order_stock_id');

        $this->dropIndex('idx-refund-order_company_own_id', 'refund');
        $this->dropColumn('refund', 'order_company_own_id');
    }
}
