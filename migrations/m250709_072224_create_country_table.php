<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%country}}`.
 */
class m250709_072224_create_country_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%country}}', [
            'id' => $this->primaryKey(),
            'alfa2' => $this->string(2)->null()->unique()->comment('Код'),
            'name' => $this->string(30)->notNull()->unique()->comment('Название'),
            'full_name' => $this->string(360)->notNull()->unique()->comment('Полное название'),
            'inn_legal_name' => $this->string(10)->notNull()->defaultValue('ИНН')
                ->comment('Название ID для Юр. лица'),
            'inn_legal_size' => $this->smallInteger()->notNull()->defaultValue(10)
                ->comment('Ширина ID для Юр. лица'),
            'inn_name' => $this->string(10)->notNull()->defaultValue('ИНН')
                ->comment('Название ID для Физ. лица'),
            'inn_size' => $this->smallInteger()->notNull()->defaultValue(12)
                ->comment('Ширина ID для Физ. лица'),
        ]);

        $this->insert('{{%country}}', [
            'alfa2' => 'RU',
            'name' => 'Россия',
            'full_name' => 'Российская Федерация',
            'inn_legal_name' => 'ИНН',
            'inn_legal_size' => 10,
            'inn_name' => 'ИНН',
            'inn_size' => 12,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%country}}');
    }
}
