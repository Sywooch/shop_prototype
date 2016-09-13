<?php

use yii\db\Migration;

/**
 * Handles the creation for table `users`.
 */
class m160913_172703_create_users_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('users', [
            'id' => $this->primaryKey(5)->unsigned()->notNull(),
            'id_email'=>$this->integer(5)->unsigned()->notNull(),
            'password'=>$this->string(255)->notNull(),
            'name'=>$this->string(100)->notNull(),
            'surname'=>$this->string(100)->notNull(),
            'id_phone'=>$this->integer(5)->unsigned()->notNull(),
            'id_address'=>$this->integer(5)->unsigned()->notNull()
        ], 'ENGINE=InnoDB');
        
        $this->addForeignKey('users_id_email', 'users', 'id_email', 'emails', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('users_id_email', 'users');
        
        $this->dropTable('users');
    }
}
