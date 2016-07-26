<?php

use yii\db\Schema;
use yii\db\Migration;

class m150625_091357_message extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%message}}', [
            'id' => Schema::TYPE_PK,
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'alias' => Schema::TYPE_STRING . ' NOT NULL',
            'title' => Schema::TYPE_STRING . ' NOT NULL',
            'content' => Schema::TYPE_BINARY,
        ],
            $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%message}}');
    }
}
