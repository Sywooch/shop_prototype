<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\models\ProductsColorsModel;

/**
 * Тестирует класс ProductsColorsModel
 */
class ProductsColorsModelTests extends TestCase
{
    /**
     * Тестирует свойства ProductsColorsModel
     */
    public function testProperties()
    {
        $model = new ProductsColorsModel();
        
        $this->assertArrayHasKey('id_product', $model->attributes);
        $this->assertArrayHasKey('id_color', $model->attributes);
    }
    
    /**
     * Тестирует метод ProductsColorsModel::tableName
     */
    public function testTableName()
    {
        $result = ProductsColorsModel::tableName();
        
        $this->assertSame('products_colors', $result);
    }
}
