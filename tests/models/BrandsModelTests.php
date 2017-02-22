<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\models\BrandsModel;

/**
 * Тестирует класс BrandsModel
 */
class BrandsModelTests extends TestCase
{
    /**
     * Тестирует свойства BrandsModel
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(BrandsModel::class);
        
        $this->assertTrue($reflection->hasConstant('DELETE'));
        
        $model = new BrandsModel();
        
        $this->assertArrayHasKey('id', $model->attributes);
        $this->assertArrayHasKey('brand', $model->attributes);
    }
    
    /**
     * Тестирует метод BrandsModel::tableName
     */
    public function testTableName()
    {
        $result = BrandsModel::tableName();
        
        $this->assertSame('brands', $result);
    }
    
    /**
     * Тестирует метод BrandsModel::scenarios
     */
    public function testScenarios()
    {
        $model = new BrandsModel(['scenario'=>BrandsModel::DELETE]);
        $model->attributes = [
            'id'=>23
        ];
        
        $this->assertEquals(23, $model->id);
    }
    
    /**
     * Тестирует метод BrandsModel::rules
     */
    public function testRules()
    {
        $model = new BrandsModel(['scenario'=>BrandsModel::DELETE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertCount(1, $model->errors);
        
        $model = new BrandsModel(['scenario'=>BrandsModel::DELETE]);
        $model->attributes = [
            'id'=>23
        ];
        $model->validate();
        
        $this->assertEmpty($model->errors);
    }
}
