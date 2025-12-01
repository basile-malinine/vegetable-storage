<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%temperature_regime}}`.
 */
class m251201_005646_create_temperature_regime_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%temperature_regime}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(30)->notNull()->unique()->comment('Название'),
            'comment' => $this->text()->null()->defaultValue(null)->comment('Комментарий'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%temperature_regime}}');
    }
}
