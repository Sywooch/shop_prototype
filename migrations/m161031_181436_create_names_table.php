<?php

use yii\db\Migration;

/**
 * Handles the creation of table `names`.
 */
class m161031_181436_create_names_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('names', [
            'id' => $this->primaryKey(5)->unsigned()->notNull(),
            'name'=>$this->string(255)->notNull()->unique()
        ], 'ENGINE=InnoDB');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('names');
    }
}
