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
        $reflection = new \ReflectionClass(ProductsColorsModel::class);
        
        $this->assertTrue($reflection->hasConstant('SAVE'));
        $this->assertTrue($reflection->hasConstant('DELETE'));
        
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
    
    /**
     * Тестирует метод ProductsColorsModel::scenarios
     */
    public function testScenarios()
    {
        $model = new ProductsColorsModel(['scenario'=>ProductsColorsModel::SAVE]);
        $model->attributes = [
            'id_product'=>12,
            'id_color'=>2
        ];
        
        $this->assertEquals(12, $model->id_product);
        $this->assertEquals(2, $model->id_color);
        
        $model = new ProductsColorsModel(['scenario'=>ProductsColorsModel::DELETE]);
        $model->attributes = [
            'id_product'=>12,
        ];
        
        $this->assertEquals(12, $model->id_product);
    }
    
    /**
     * Тестирует метод ProductsColorsModel::rules
     */
    public function testRules()
    {
        $model = new ProductsColorsModel(['scenario'=>ProductsColorsModel::SAVE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertCount(2, $model->errors);
        
        $model = new ProductsColorsModel(['scenario'=>ProductsColorsModel::SAVE]);
        $model->attributes = [
            'id_product'=>12,
            'id_color'=>2
        ];
        $model->validate();
        
        $this->assertEmpty($model->errors);
        
        $model = new ProductsColorsModel(['scenario'=>ProductsColorsModel::DELETE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertCount(1, $model->errors);
        
        $model = new ProductsColorsModel(['scenario'=>ProductsColorsModel::DELETE]);
        $model->attributes = [
            'id_product'=>12,
        ];
        $model->validate();
        
        $this->assertEmpty($model->errors);
    }
}
