<?php

use yii\db\Migration;

/**
 * Handles the creation for table `products_brands`.
 * Has foreign keys to the tables:
 *
 * - `products`
 * - `brands`
 */
class m160913_144334_create_junction_table_for_products_and_brands_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('products_brands', [
            'id_product' => $this->integer(5)->unsigned()->notNull(),
            'id_brand' => $this->integer(3)->unsigned()->notNull(),
        ], 'ENGINE=InnoDB');
        
        $this->createIndex('products_brands_id_product_id_brand', 'products_brands', ['id_product', 'id_brand'], true);
        
        $this->addForeignKey('products_brands_id_product', 'products_brands', 'id_product', 'products', 'id', 'CASCADE', 'CASCADE');
        
        $this->addForeignKey('products_brands_id_brand', 'products_brands', 'id_brand', 'brands', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('products_brands_id_brand', 'products_brands');
        
        $this->dropForeignKey('products_brands_id_product', 'products_brands');
        
        $this->dropIndex('products_brands_id_product_id_brand', 'products_brands');
        
        $this->dropTable('products_brands');
    }
}
