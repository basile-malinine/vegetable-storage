<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%system_object_google_sheet}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%system_object}}`
 * - `{{%google_sheet}}`
 */
class m251205_143735_create_junction_table_for_system_object_and_google_sheet_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%system_object_google_sheet}}', [
            'system_object_id' => $this->integer(),
            'google_sheet_id' => $this->integer(),
            'PRIMARY KEY(system_object_id, google_sheet_id)',
            'google_sheet_range' => $this->string(50)->null()->comment('Диапазон для таблицы Google'),
            'comment' => $this->text()->null()->comment('Комментарий'),
        ]);

        // creates index for column `system_object_id`
        $this->createIndex(
            '{{%idx-system_object_google_sheet-system_object_id}}',
            '{{%system_object_google_sheet}}',
            'system_object_id'
        );

        // add foreign key for table `{{%system_object}}`
        $this->addForeignKey(
            '{{%fk-system_object_google_sheet-system_object_id}}',
            '{{%system_object_google_sheet}}',
            'system_object_id',
            '{{%system_object}}',
            'id',
            'CASCADE'
        );

        // creates index for column `google_sheet_id`
        $this->createIndex(
            '{{%idx-system_object_google_sheet-google_sheet_id}}',
            '{{%system_object_google_sheet}}',
            'google_sheet_id'
        );

        // add foreign key for table `{{%google_sheet}}`
        $this->addForeignKey(
            '{{%fk-system_object_google_sheet-google_sheet_id}}',
            '{{%system_object_google_sheet}}',
            'google_sheet_id',
            '{{%google_sheet}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%system_object}}`
        $this->dropForeignKey(
            '{{%fk-system_object_google_sheet-system_object_id}}',
            '{{%system_object_google_sheet}}'
        );

        // drops index for column `system_object_id`
        $this->dropIndex(
            '{{%idx-system_object_google_sheet-system_object_id}}',
            '{{%system_object_google_sheet}}'
        );

        // drops foreign key for table `{{%google_sheet}}`
        $this->dropForeignKey(
            '{{%fk-system_object_google_sheet-google_sheet_id}}',
            '{{%system_object_google_sheet}}'
        );

        // drops index for column `google_sheet_id`
        $this->dropIndex(
            '{{%idx-system_object_google_sheet-google_sheet_id}}',
            '{{%system_object_google_sheet}}'
        );

        $this->dropTable('{{%system_object_google_sheet}}');
    }
}
