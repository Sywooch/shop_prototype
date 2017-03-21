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
            'code'=>$this->char(3)->notNull()->unique(),
            'exchange_rate'=>$this->decimal(9, 5)->notNull()->defaultValue(1.00000),
            'main'=>$this->boolean()->notNull()->defaultValue(false),
            'update_date'=>$this->integer(10)->unsigned()->notNull()->defaultValue(0),
            'symbol'=>$this->string(10)->notNull()->defaultValue(''),
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
