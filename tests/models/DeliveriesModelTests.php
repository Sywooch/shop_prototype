<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\models\DeliveriesModel;

/**
 * Тестирует класс DeliveriesModel
 */
class DeliveriesModelTests extends TestCase
{
    /**
     * Тестирует свойства DeliveriesModel
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(DeliveriesModel::class);
        
        $this->assertTrue($reflection->hasConstant('DELETE'));
        $this->assertTrue($reflection->hasConstant('CREATE'));
        
        $model = new DeliveriesModel();
        
        $this->assertArrayHasKey('id', $model->attributes);
        $this->assertArrayHasKey('name', $model->attributes);
        $this->assertArrayHasKey('description', $model->attributes);
        $this->assertArrayHasKey('price', $model->attributes);
        $this->assertArrayHasKey('active', $model->attributes);
    }
    
    /**
     * Тестирует метод DeliveriesModel::tableName
     */
    public function testTableName()
    {
        $result = DeliveriesModel::tableName();
        
        $this->assertSame('deliveries', $result);
    }
    
    /**
     * Тестирует метод DeliveriesModel::scenarios
     */
    public function testScenarios()
    {
        $model = new DeliveriesModel(['scenario'=>DeliveriesModel::DELETE]);
        $model->attributes = [
            'id'=>23
        ];
        
        $this->assertEquals(23, $model->id);
        
        $model = new DeliveriesModel(['scenario'=>DeliveriesModel::CREATE]);
        $model->attributes = [
            'name'=>'name',
            'description'=>'description',
            'price'=>1.23,
            'active'=>1
        ];
        
        $this->assertEquals('name', $model->name);
        $this->assertEquals('description', $model->description);
        $this->assertEquals(1.23, $model->price);
        $this->assertEquals(1, $model->active);
    }
    
    /**
     * Тестирует метод DeliveriesModel::rules
     */
    public function testRules()
    {
        $model = new DeliveriesModel(['scenario'=>DeliveriesModel::DELETE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertCount(1, $model->errors);
        
        $model = new DeliveriesModel(['scenario'=>DeliveriesModel::DELETE]);
        $model->attributes = [
            'id'=>23
        ];
        $model->validate();
        
        $this->assertEmpty($model->errors);
        
        $model = new DeliveriesModel(['scenario'=>DeliveriesModel::CREATE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertCount(3, $model->errors);
        
        $model = new DeliveriesModel(['scenario'=>DeliveriesModel::CREATE]);
        $model->attributes = [
            'name'=>'name',
            'description'=>'description',
            'price'=>1.23,
        ];
        $model->validate();
        
        $this->assertEmpty($model->errors);
        $this->assertSame(0, $model->active);
    }
}
