<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%legal_subject}}`.
 */
class m251028_155731_create_legal_subject_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%legal_subject}}', [
            'id' => $this->primaryKey(),
            'country_id' => $this->integer()->notNull()->defaultValue(1)->comment('Страна'),
            'is_legal' => $this->smallInteger()->notNull()->defaultValue(1)->comment('Юридическое лицо'),
            'is_own' => $this->smallInteger()->notNull()->defaultValue(0)->comment('Собственное предприятие'),
            'is_supplier' => $this->smallInteger()->notNull()->defaultValue(0)->comment('Поставщик'),
            'is_buyer' => $this->smallInteger()->notNull()->defaultValue(0)->comment('Покупатель'),
            'name' => $this->string(30)->notNull()->comment('Краткое название предприятия или ФИО'),
            'full_name' => $this->string(100)->notNull()->comment('Полное название предприятия или ФИО'),
            'inn' => $this->string(12)->notNull()->comment('ИНН'),
            'director' => $this->string(255)->null()->defaultValue(null)->comment('Директор'),
            'accountant' => $this->string(255)->null()->defaultValue(null)->comment('Бухгалтер'),
            'address' => $this->string(255)->null()->defaultValue(null)->comment('Адрес'),
            'contacts' => $this->text()->null()->defaultValue(null)->comment('Контактная информация'),
            'comment' => $this->text()->null()->defaultValue(null)->comment('Комментарий'),
        ]);

        $this->createIndex(
            '{{%idx-legal_subject-country_id}}',
            '{{%legal_subject}}',
            'country_id'
        );

        $this->addForeignKey(
            '{{%fk-legal_subject-country_id}}',
            '{{%legal_subject}}',
            'country_id',
            '{{%country}}',
            'id',
            'NO ACTION',
            'NO ACTION'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-legal_subject-country_id}}', '{{%legal_subject}}');
        $this->dropIndex('{{%idx-legal_subject-country_id}}', '{{%legal_subject}}');
        $this->dropTable('{{%legal_subject}}');
    }
}
