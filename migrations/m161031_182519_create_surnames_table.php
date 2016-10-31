<?php

use yii\db\Migration;

/**
 * Handles the creation of table `surnames`.
 */
class m161031_182519_create_surnames_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('surnames', [
            'id' => $this->primaryKey(5)->unsigned()->notNull(),
            'surname'=>$this->string(255)->notNull()->unique(),
        ], 'ENGINE=InnoDB');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('surnames');
    }
}
