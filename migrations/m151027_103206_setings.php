<?php

use yii\db\Migration;

class m151027_103206_setings extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%settings}}', [
            'id' => $this->primaryKey(11),
            'email_callback' => $this->string(255),
            'email_noreply' => $this->string(255),
            'yandex_webmaster' => $this->string(255),
            'google_webmaster' => $this->text(),
            'metrika_code' => $this->text(),
            'analitics_code' => $this->text(),
        ], $tableOptions);

        $this->insert('{{%settings}}', [
            'email_callback' => '',
            'email_noreply' => '',
            'yandex_webmaster' => '',
            'google_webmaster' => '',
            'metrika_code' => '',
            'analitics_code' => '',
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%settings}}');
    }
}
