<?php

use yii\db\Migration;

/**
 * Handles the creation of table `postcodes`.
 */
class m161031_183939_create_postcodes_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('postcodes', [
            'id' => $this->primaryKey(5)->unsigned()->notNull(),
            'postcode'=>$this->string(20)->notNull()->unique(),
        ], 'ENGINE=InnoDB');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('postcodes');
    }
}
