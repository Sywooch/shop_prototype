<?php

use yii\db\Migration;

/**
 * Handles the creation for table `categories`.
 */
class m160913_085126_create_categories_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('categories', [
            'id'=>$this->primaryKey(3)->unsigned()->notNull(),
            'name'=>$this->string(255)->notNull()->unique(),
            'seocode'=>$this->string(255)->notNull()->unique(),
            'active'=>$this->boolean()->notNull()->defaultValue(true)
        ], 'ENGINE=InnoDB');
        
        $this->createIndex('categories_name_seocode', 'categories', ['name', 'seocode'], true);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropIndex('categories_name_seocode', 'categories');
        
        $this->dropTable('categories');
    }
}
