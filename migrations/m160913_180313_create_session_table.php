<?php

use yii\db\Migration;

/**
 * Handles the creation for table `session`.
 */
class m160913_180313_create_session_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('session', [
            'id' => $this->char(40)->notNull(),
            'expire'=>$this->integer()->notNull(),
            'data'=>$this->binary()->notNull()
        ]);
        
        $this->addPrimaryKey('session_id', 'session', 'id');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropPrimaryKey('session_id', 'session');
        
        $this->dropTable('session');
    }
}
