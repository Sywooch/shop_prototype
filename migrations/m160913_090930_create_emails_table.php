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
    public function up()
    {
        $this->createTable('emails', [
            'id' => $this->primaryKey(5)->unsigned()->notNull(),
            'email'=>$this->string(255)->notNull()->unique()
        ], 'ENGINE=InnoDB');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('emails');
    }
}
