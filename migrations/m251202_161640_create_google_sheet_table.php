<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%google_sheet}}`.
 */
class m251202_161640_create_google_sheet_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%google_sheet}}', [
            'id' => $this->primaryKey(),
            'sheet_id' => $this->string(60)->notNull()->unique()->comment('Sheet ID'),
            'name' => $this->string(120)->notNull()->comment('Название'),
            'comment' => $this->text()->null()->defaultValue(null)->comment('Комментарий'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%google_sheet}}');
    }
}
