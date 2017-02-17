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
        $reflection = new \ReflectionClass(ProductsSizesModel::class);
        
        $this->assertTrue($reflection->hasConstant('SAVE'));
        $this->assertTrue($reflection->hasConstant('DELETE'));
        
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
    
    /**
     * Тестирует метод ProductsSizesModel::scenarios
     */
    public function testScenarios()
    {
        $model = new ProductsSizesModel(['scenario'=>ProductsSizesModel::SAVE]);
        $model->attributes = [
            'id_product'=>12,
            'id_size'=>2
        ];
        
        $this->assertEquals(12, $model->id_product);
        $this->assertEquals(2, $model->id_size);
        
        $model = new ProductsSizesModel(['scenario'=>ProductsSizesModel::DELETE]);
        $model->attributes = [
            'id_product'=>12,
        ];
        
        $this->assertEquals(12, $model->id_product);
    }
    
    /**
     * Тестирует метод ProductsSizesModel::rules
     */
    public function testRules()
    {
        $model = new ProductsSizesModel(['scenario'=>ProductsSizesModel::SAVE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertCount(2, $model->errors);
        
        $model = new ProductsSizesModel(['scenario'=>ProductsSizesModel::SAVE]);
        $model->attributes = [
            'id_product'=>12,
            'id_size'=>2
        ];
        $model->validate();
        
        $this->assertEmpty($model->errors);
        
        $model = new ProductsSizesModel(['scenario'=>ProductsSizesModel::DELETE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertCount(1, $model->errors);
        
        $model = new ProductsSizesModel(['scenario'=>ProductsSizesModel::DELETE]);
        $model->attributes = [
            'id_product'=>12,
        ];
        $model->validate();
        
        $this->assertEmpty($model->errors);
    }
}
