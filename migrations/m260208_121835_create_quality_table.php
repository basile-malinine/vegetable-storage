<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%quality}}`.
 */
class m260208_121835_create_quality_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%quality}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(30)->notNull()->unique()->comment('Название'),
            'comment' => $this->text()->null()->defaultValue(null)->comment('Комментарий'),
        ]);
        $this->addCommentOnTable('quality', 'Качество');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%quality}}');
    }
}
