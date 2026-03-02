<?php

use yii\db\Migration;

class m260302_002916_alter_inn_column_in_legal_subject_table extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('legal_subject','inn',
            $this->string(30)->notNull()->comment('ИНН'));
    }

    public function safeDown()
    {
        $this->alterColumn('legal_subject','inn',
            $this->string(12)->notNull()->comment('ИНН'));
    }
}
