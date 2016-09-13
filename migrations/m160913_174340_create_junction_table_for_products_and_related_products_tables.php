<?php

use yii\db\Migration;

/**
 * Handles the creation for table `products_related_products`.
 * Has foreign keys to the tables:
 *
 * - `products`
 * - `related_products`
 */
class m160913_174340_create_junction_table_for_products_and_related_products_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('related_products', [
            'id_product' => $this->integer(5)->unsigned()->notNull(),
            'id_related_product' => $this->integer(5)->unsigned()->notNull(),
        ], 'ENGINE=InnoDB');
        
        $this->createIndex('related_products_id_product_id_related_product', 'related_products', ['id_product', 'id_related_product'], true);
        
        $this->addForeignKey('related_products_id_product', 'related_products', 'id_product', 'products', 'id', 'CASCADE', 'CASCADE');
        
        $this->addForeignKey('related_products_id_related_product', 'related_products', 'id_related_product', 'products', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('related_products_id_related_product', 'related_products');
        
        $this->dropForeignKey('related_products_id_product', 'related_products');
        
        $this->dropIndex('related_products_id_product_id_related_product', 'related_products');
        
        $this->dropTable('related_products');
    }
}
