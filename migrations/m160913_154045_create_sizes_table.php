<?php

use yii\db\Migration;

/**
 * Handles the creation for table `sizes`.
 */
class m160913_154045_create_sizes_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('sizes', [
            'id' => $this->primaryKey(3)->unsigned()->notNull(),
            'size'=>$this->decimal(4, 1)->notNull()->defaultValue(0)->unique(),
        ], 'ENGINE=InnoDB');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('sizes');
    }
}
