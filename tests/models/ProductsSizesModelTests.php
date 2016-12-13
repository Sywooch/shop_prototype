<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\models\ProductsSizesModel;

/**
 * Тестирует класс ProductsSizesModel
 */
class ProductsSizesModelTests extends TestCase
{
    /**
     * Тестирует свойства ProductsSizesModel
     */
    public function testProperties()
    {
        $model = new ProductsSizesModel();
        
        $this->assertArrayHasKey('id_product', $model->attributes);
        $this->assertArrayHasKey('id_size', $model->attributes);
    }
    
    /**
     * Тестирует метод ProductsSizesModel::tableName
     */
    public function testTableName()
    {
        $result = ProductsSizesModel::tableName();
        
        $this->assertSame('products_sizes', $result);
    }
}
