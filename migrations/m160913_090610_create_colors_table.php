<?php

use yii\db\Migration;

/**
 * Handles the creation for table `colors`.
 */
class m160913_090610_create_colors_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('colors', [
            'id' => $this->primaryKey(3)->unsigned()->notNull(),
            'color'=>$this->string(25)->notNull()->unique(),
            'hexcolor'=>$this->char(7)->notNull()->defaultValue(''),
        ], 'ENGINE=InnoDB');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('colors');
    }
}
