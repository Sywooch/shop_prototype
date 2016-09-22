<?php

use yii\db\Migration;

/**
 * Handles adding seocode to table `products`.
 */
class m160922_081536_add_seocode_column_and_key_for_code_column_to_products_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('products', 'seocode', $this->string(255)->notNull());
        $this->createIndex('seocode', 'products', 'seocode(255)', true);
        
        $this->createIndex('code', 'products', 'code(100)', true);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropIndex('seocode', 'products');
        $this->dropColumn('products', 'seocode');
    }
}
