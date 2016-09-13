<?php

use yii\db\Migration;

/**
 * Handles the creation for table `phones`.
 */
class m160913_142756_create_phones_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('phones', [
            'id' => $this->primaryKey(5)->unsigned()->notNull(),
            'phone'=>$this->string(30)->notNull()->unique()
        ], 'ENGINE=InnoDB');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('phones');
    }
}
