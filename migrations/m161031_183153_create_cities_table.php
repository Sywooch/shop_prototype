<?php

use yii\db\Migration;

/**
 * Handles the creation of table `cities`.
 */
class m161031_183153_create_cities_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('cities', [
            'id' => $this->primaryKey(3)->unsigned()->notNull(),
            'city'=>$this->string(255)->notNull()->unique(),
        ], 'ENGINE=InnoDB');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('cities');
    }
}
