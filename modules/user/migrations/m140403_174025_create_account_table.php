<?php

use yii\db\Migration;
use yii\db\Schema;

class m140403_174025_create_account_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%social_account}}', [
            'id' => Schema::TYPE_PK,
            'user_id' => Schema::TYPE_INTEGER,
            'provider' => Schema::TYPE_STRING . ' NOT NULL',
            'client_id' => Schema::TYPE_STRING . ' NOT NULL',
            'data' => Schema::TYPE_TEXT,
        ], $tableOptions);

        $this->createIndex('account_unique', '{{%social_account}}', ['provider', 'client_id'], true);
        $this->addForeignKey('fk_user_account', '{{%social_account}}', 'user_id', '{{%user}}', 'id', 'CASCADE',
            'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%social_account}}');
    }
}
