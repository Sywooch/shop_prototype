<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\models\PaymentsModel;

/**
 * Тестирует класс PaymentsModel
 */
class PaymentsModelTests extends TestCase
{
    /**
     * Тестирует свойства PaymentsModel
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(PaymentsModel::class);
        
        $this->assertTrue($reflection->hasConstant('DELETE'));
        $this->assertTrue($reflection->hasConstant('CREATE'));
        $this->assertTrue($reflection->hasConstant('EDIT'));
        
        $model = new PaymentsModel();
        
        $this->assertArrayHasKey('id', $model->attributes);
        $this->assertArrayHasKey('name', $model->attributes);
        $this->assertArrayHasKey('description', $model->attributes);
    }
    
    /**
     * Тестирует метод PaymentsModel::tableName
     */
    public function testTableName()
    {
        $result = PaymentsModel::tableName();
        
        $this->assertSame('payments', $result);
    }
    
    /**
     * Тестирует метод PaymentsModel::scenarios
     */
    public function testScenarios()
    {
        $model = new PaymentsModel(['scenario'=>PaymentsModel::DELETE]);
        $model->attributes = [
            'id'=>23
        ];
        
        $this->assertEquals(23, $model->id);
        
        $model = new PaymentsModel(['scenario'=>PaymentsModel::CREATE]);
        $model->attributes = [
            'name'=>'name',
            'description'=>'description',
            'active'=>1
        ];
        
        $this->assertEquals('name', $model->name);
        $this->assertEquals('description', $model->description);
        $this->assertEquals(1, $model->active);
        
        $model = new PaymentsModel(['scenario'=>PaymentsModel::EDIT]);
        $model->attributes = [
            'id'=>45,
            'name'=>'name',
            'description'=>'description',
            'active'=>1
        ];
        
        $this->assertEquals(45, $model->id);
        $this->assertEquals('name', $model->name);
        $this->assertEquals('description', $model->description);
        $this->assertEquals(1, $model->active);
    }
    
    /**
     * Тестирует метод PaymentsModel::rules
     */
    public function testRules()
    {
        $model = new PaymentsModel(['scenario'=>PaymentsModel::DELETE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertCount(1, $model->errors);
        
        $model = new PaymentsModel(['scenario'=>PaymentsModel::DELETE]);
        $model->attributes = [
            'id'=>23
        ];
        $model->validate();
        
        $this->assertEmpty($model->errors);
        
        $model = new PaymentsModel(['scenario'=>PaymentsModel::CREATE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertCount(2, $model->errors);
        
        $model = new PaymentsModel(['scenario'=>PaymentsModel::CREATE]);
        $model->attributes = [
            'name'=>'name',
            'description'=>'description',
        ];
        $model->validate();
        
        $this->assertEmpty($model->errors);
        $this->assertSame(0, $model->active);
        
        $model = new PaymentsModel(['scenario'=>PaymentsModel::EDIT]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertCount(3, $model->errors);
        
        $model = new PaymentsModel(['scenario'=>PaymentsModel::EDIT]);
        $model->attributes = [
            'id'=>45,
            'name'=>'name',
            'description'=>'description',
        ];
        $model->validate();
        
        $this->assertEmpty($model->errors);
    }
}
