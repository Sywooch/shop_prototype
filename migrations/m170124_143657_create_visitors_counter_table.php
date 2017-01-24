<?php

use yii\db\Migration;

/**
 * Handles the creation of table `visitors_counter`.
 */
class m170124_143657_create_visitors_counter_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('visitors_counter', [
            'date' => $this->primaryKey(10)->unsigned()->notNull(),
            'counter'=>$this->integer(10)->unsigned()->notNull()->defaultValue(0)
        ], 'ENGINE=InnoDB');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('visitors_counter');
    }
}
