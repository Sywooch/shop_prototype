<?php

use yii\db\Migration;

/**
 * Handles the creation for table `rules`.
 */
class m160913_175943_create_rules_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('rules', [
            'id' => $this->primaryKey(3)->unsigned()->notNull(),
            'rule'=>$this->string(255)->notNull()->unique()
        ], 'ENGINE=InnoDB');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('rules');
    }
}
