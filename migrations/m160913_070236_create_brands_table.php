<?php

use yii\db\Migration;

/**
 * Handles the creation for table `brands`.
 */
class m160913_070236_create_brands_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('brands', [
            'id' => $this->primaryKey(3)->unsigned()->notNull(),
            'brand'=>$this->string(255)->notNull()->unique(),
        ], 'ENGINE=InnoDB');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('brands');
    }
}
