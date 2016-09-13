<?php

use yii\db\Migration;

/**
 * Handles the creation for table `subcategory`.
 */
class m160913_091219_create_subcategory_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('subcategory', [
            'id'=>$this->primaryKey(3)->unsigned()->notNull(),
            'name'=>$this->string(255)->notNull()->unique(),
            'seocode'=>$this->string(255)->notNull()->unique(),
            'id_category'=>$this->integer(3)->unsigned()->notNull()
        ], 'ENGINE=InnoDB');
        
        $this->createIndex('subcategory_name_seocode', 'subcategory', ['name', 'seocode'], true);
        
        $this->addForeignKey('subcategory_id_category', 'subcategory', 'id_category', 'categories', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('subcategory_id_category', 'subcategory');
        
        $this->dropIndex('subcategory_name_seocode', 'subcategory');
        
        $this->dropTable('subcategory');
    }
}
