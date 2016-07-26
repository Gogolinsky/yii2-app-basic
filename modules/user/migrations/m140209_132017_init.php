<?php

use yii\db\Migration;
use yii\db\Schema;

class m140209_132017_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => Schema::TYPE_PK,
            'email' => Schema::TYPE_STRING . '(255) NOT NULL',
            'password_hash' => Schema::TYPE_STRING . '(60) NOT NULL',
            'auth_key' => Schema::TYPE_STRING . '(32) NOT NULL',
            'confirmed_at' => Schema::TYPE_INTEGER,
            'unconfirmed_email' => Schema::TYPE_STRING . '(255)',
            'registration_ip' => Schema::TYPE_BIGINT,
            'flags' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'blocked_at' => Schema::TYPE_INTEGER . ' DEFAULT NULL',
        ], $tableOptions);

        $this->createIndex('user_unique_email', '{{%user}}', 'email', true);

        $this->createTable('{{%profile}}', [
            'user_id' => Schema::TYPE_INTEGER . ' PRIMARY KEY',
            'name' => Schema::TYPE_STRING . '(255)',
            'last_name' => Schema::TYPE_STRING . '(255)',
            'public_email' => Schema::TYPE_STRING . '(255)',
            'gravatar_email' => Schema::TYPE_STRING . '(255)',
            'gravatar_id' => Schema::TYPE_STRING . '(32)',
            'location' => Schema::TYPE_STRING . '(255)',
            'website' => Schema::TYPE_STRING . '(255)',
            'bio' => Schema::TYPE_TEXT,
        ], $tableOptions);

        $this->addForeignKey('fk_user_profile', '{{%profile}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%profile}}');
        $this->dropTable('{{%user}}');
    }
}
