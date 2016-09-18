<?php

use yii\db\Migration;

/**
 * Handles the creation for table `currency`.
 */
class m160913_132853_create_currency_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('currency', [
            'id' => $this->primaryKey(3)->unsigned()->notNull(),
            'currency'=>$this->char(3)->notNull()->unique(),
            'exchange_rate'=>$this->decimal(9, 5)->notNull()->defaultValue(1.00000),
            'main'=>$this->boolean()->notNull()->defaultValue(false)
        ], 'ENGINE=InnoDB');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('currency');
    }
}