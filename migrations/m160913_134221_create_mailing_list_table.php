<?php

use yii\db\Migration;

/**
 * Handles the creation for table `mailing_list`.
 */
class m160913_134221_create_mailing_list_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('mailing_list', [
            'id' => $this->primaryKey(3)->unsigned()->notNull(),
            'name'=>$this->string(255)->notNull()->unique(),
            'description'=>$this->string(500)->notNull()
        ], 'ENGINE=InnoDB');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('mailing_list');
    }
}
