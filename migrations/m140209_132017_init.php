<?php

use yii\db\Migration;

class m140209_132017_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'email' => $this->string(255)->notNull(),
            'password_hash' => $this->string(60)->notNull(),
            'auth_key' => $this->string(32)->notNull(),
            'confirmed_at' => $this->integer(11),
            'unconfirmed_email' => $this->string(255),
            'registration_ip' => $this->bigInteger(),
            'flags' => $this->integer(11)->notNull()->defaultValue(0),
            'created_at' => $this->integer(11)->notNull(),
            'updated_at' => $this->integer(11)->notNull(),
            'blocked_at' => $this->integer(11)->defaultValue(null),
        ], $tableOptions);

        $this->createIndex('user_unique_email', '{{%user}}', 'email', true);

        $this->createTable('{{%profile}}', [
            'user_id' => $this->primaryKey(),
            'name' => $this->string(255),
            'last_name' => $this->string(255),
            'public_email' => $this->string(255),
            'gravatar_email' => $this->string(255),
            'gravatar_id' => $this->string(32),
            'location' => $this->string(255),
            'website' => $this->string(255),
            'bio' => $this->text(),
        ], $tableOptions);

        $this->addForeignKey('fk_user_profile', '{{%profile}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%token}}', [
            'user_id' => $this->integer()->notNull(),
            'code' => $this->string(32)->notNull(),
            'created_at' => $this->string(11)->notNull(),
            'type' => $this->smallInteger(32)->notNull(),
        ], $tableOptions);

        $this->createIndex('token_unique', '{{%token}}', ['user_id', 'code', 'type'], true);
        $this->addForeignKey('fk_user_token', '{{%token}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');

        $this->insert('{{%user}}', [
            'created_at' => time(),
            'updated_at' => time(),
            'blocked_at' => null,
            'email' => 'manager@dancecolor.ru',
            'password_hash' => '$2y$13$iJkbOlZgYfzRddMquXGttOWCLK4AmDVUrkM1.wLbz8odk/.myEh7y',
            'confirmed_at' => time(),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%profile}}');
        $this->dropTable('{{%user}}');
        $this->dropTable('{{%token}}');
    }
}
