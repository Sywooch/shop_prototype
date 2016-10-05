<?php

use yii\db\Migration;

/**
 * Handles the creation for table `emails`.
 */
class m160913_090930_create_emails_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('emails', [
            'id' => $this->primaryKey(5)->unsigned()->notNull(),
            'email'=>$this->string(255)->notNull()->unique()
        ], 'ENGINE=InnoDB');
        
        $this->createIndex('email', 'emails', 'email', true);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('emails');
    }
}
