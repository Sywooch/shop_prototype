<?php

use yii\db\Migration;

/**
 * Handles the creation of table `countries`.
 */
class m161031_183551_create_countries_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('countries', [
            'id' => $this->primaryKey(3)->unsigned()->notNull(),
            'country'=>$this->string(255)->notNull()->unique(),
        ], 'ENGINE=InnoDB');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('countries');
    }
}
