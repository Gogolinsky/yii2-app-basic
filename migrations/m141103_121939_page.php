<?php

use yii\db\Migration;

/**
 * Class m141103_121939_page
 */
class m141103_121939_page extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%page}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->string(255)->notNull(),
            'updated_at' => $this->string(255)->notNull(),
            'lft' => $this->integer(11)->notNull(),
            'rgt' => $this->integer(11)->notNull(),
            'depth' => $this->integer(11)->notNull(),
            'tree' => $this->integer(11)->notNull(),
            'title' => $this->string(255)->notNull(),
            'alias' => $this->string(255)->notNull(),
            'content' => $this->binary(),
            'meta_d' => $this->text()->defaultValue(null),
            'meta_k' => $this->text()->defaultValue(null),
            'meta_t' => $this->text()->defaultValue(null),
        ],
            $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%page}}');
    }
}
