<?php

use yii\db\Migration;

/**
 * Handles the creation for table `mailings`.
 */
class m160913_134221_create_mailings_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('mailings', [
            'id' => $this->primaryKey(3)->unsigned()->notNull(),
            'name'=>$this->string(255)->notNull()->unique(),
            'description'=>$this->string(500)->notNull(),
            'active'=>$this->boolean()->defaultValue(0)
        ], 'ENGINE=InnoDB');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('mailings');
    }
}
