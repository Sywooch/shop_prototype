<?php

use yii\db\Migration;

/**
 * Handles the creation for table `products_sizes`.
 * Has foreign keys to the tables:
 *
 * - `products`
 * - `sizes`
 */
class m160913_154455_create_junction_table_for_products_and_sizes_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('products_sizes', [
            'id_product' => $this->integer(5)->unsigned()->notNull(),
            'id_size' => $this->integer(3)->unsigned()->notNull(),
        ], 'ENGINE=InnoDB');
        
        $this->createIndex('products_sizes_id_product_id_size', 'products_sizes', ['id_product', 'id_size'], true);
        
        $this->addForeignKey('products_sizes_id_product', 'products_sizes', 'id_product', 'products', 'id', 'CASCADE', 'CASCADE');
        
        $this->addForeignKey('products_sizes_id_size', 'products_sizes', 'id_size', 'sizes', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('products_sizes_id_size', 'products_sizes');
        
        $this->dropForeignKey('products_sizes_id_product', 'products_sizes');
        
        $this->dropIndex('products_sizes_id_product_id_size', 'products_sizes');
        
        $this->dropTable('products_sizes');
    }
}
