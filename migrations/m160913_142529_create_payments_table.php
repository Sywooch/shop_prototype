<?php

use yii\db\Migration;

/**
 * Handles the creation for table `payments`.
 */
class m160913_142529_create_payments_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('payments', [
            'id' => $this->primaryKey(3)->unsigned()->notNull(),
            'name'=>$this->string(255)->notNull()->unique(),
            'description'=>$this->string(1000)->notNull(),
            'active'=>$this->boolean()->defaultValue(0),
        ], 'ENGINE=InnoDB');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('payments');
    }
}
