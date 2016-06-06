<?php

use yii\db\Migration;
use yii\db\Schema;

class m160529_130107_like extends Migration
{
    public function safeUp()
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable(
            '{{%like}}',
            [
                'id'=> Schema::TYPE_PK."",
                'user_id'=> Schema::TYPE_INTEGER."(11) NOT NULL",
                'model'=> Schema::TYPE_STRING."(255) NOT NULL",
                'item_id'=> Schema::TYPE_INTEGER."(11) NOT NULL",
                ],
            $tableOptions
        );

    }

    public function safeDown()
    {
        $this->dropTable('{{%like}}');
    }
}
