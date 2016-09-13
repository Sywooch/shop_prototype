<?php

use yii\db\Migration;

/**
 * Handles the creation for table `admin_menu`.
 */
class m160913_084846_create_admin_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('admin_menu', [
            'id' => $this->primaryKey(3)->unsigned()->notNull(),
            'name'=>$this->string(255)->notNull(),
            'route'=>$this->string(255)->notNull()
        ], 'ENGINE=InnoDB');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('admin_menu');
    }
}
