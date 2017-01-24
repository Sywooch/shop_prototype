<?php

use yii\db\Migration;

/**
 * Handles the creation for table `products`.
 */
class m160913_095636_create_products_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('products', [
            'id' => $this->primaryKey(5)->unsigned()->notNull(),
            'date'=>$this->integer(10)->unsigned()->notNull(),
            'code'=>$this->string(100)->notNull(),
            'name'=>$this->string(255)->notNull(),
            'short_description'=>$this->string(500)->notNull(),
            'description'=>$this->string(2000)->notNull(),
            'price'=>$this->decimal(8, 2)->notNull()->defaultValue(0.00),
            'images'=>$this->string(500)->notNull(),
            'id_category'=>$this->integer(3)->unsigned()->notNull(),
            'id_subcategory'=>$this->integer(3)->unsigned()->notNull(),
            'id_brand'=>$this->integer(3)->unsigned()->notNull(),
            'active'=>$this->boolean()->notNull()->defaultValue(true),
            'total_products'=>$this->smallInteger(5)->unsigned()->notNull()->defaultValue(0),
            'seocode'=>$this->string(255)->notNull(),
            'views'=>$this->integer(10)->unsigned()->notNull()->defaultValue(0),
        ], 'ENGINE=InnoDB');
        
        $this->createIndex('seocode', 'products', 'seocode(255)', true);
        
        $this->createIndex('code', 'products', 'code(100)', true);
        
        $this->addForeignKey('products_id_category', 'products', 'id_category', 'categories', 'id', 'RESTRICT', 'CASCADE');
        
        $this->addForeignKey('products_id_subcategory', 'products', 'id_subcategory', 'subcategory', 'id', 'RESTRICT', 'CASCADE');
        
        $this->addForeignKey('products_id_brand', 'products', 'id_brand', 'brands', 'id', 'RESTRICT', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('products_id_brand', 'products');
        
        $this->dropForeignKey('products_id_subcategory', 'products');
        
        $this->dropForeignKey('products_id_category', 'products');
        
        $this->dropIndex('code', 'products');
        
        $this->dropIndex('seocode', 'products');
        
        $this->dropTable('products');
    }
}
