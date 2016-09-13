<?php

use yii\db\Migration;

/**
 * Handles the creation for table `deliveries`.
 */
class m160913_133339_create_deliveries_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('deliveries', [
            'id' => $this->primaryKey(3)->unsigned()->notNull(),
            'name'=>$this->string(255)->notNull()->unique(),
            'description'=>$this->string(1000)->notNull(),
            'price'=>$this->decimal(6, 2)->notNull()->defaultValue(0.00)
        ], 'ENGINE=InnoDB');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('deliveries');
    }
}
