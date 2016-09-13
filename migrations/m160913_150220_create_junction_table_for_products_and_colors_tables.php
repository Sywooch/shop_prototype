<?php

use yii\db\Migration;

/**
 * Handles the creation for table `products_colors`.
 * Has foreign keys to the tables:
 *
 * - `products`
 * - `colors`
 */
class m160913_150220_create_junction_table_for_products_and_colors_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('products_colors', [
            'id_product' => $this->integer(5)->unsigned()->notNull(),
            'id_color' => $this->integer(3)->unsigned()->notNull(),
        ], 'ENGINE=InnoDB');
        
        $this->createIndex('products_colors_id_product_id_color', 'products_colors', ['id_product', 'id_color'], true);
        
        $this->addForeignKey('products_colors_id_product', 'products_colors', 'id_product', 'products', 'id', 'CASCADE', 'CASCADE');
        
        $this->addForeignKey('products_colors_id_color', 'products_colors', 'id_color', 'colors', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('products_colors_id_color', 'products_colors');
        
        $this->dropForeignKey('products_colors_id_product', 'products_colors');
        
        $this->dropIndex('products_colors_id_product_id_color', 'products_colors');
        
        $this->dropTable('products_colors');
    }
}
