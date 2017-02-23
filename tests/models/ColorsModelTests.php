<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\models\ColorsModel;

/**
 * Тестирует класс ColorsModel
 */
class ColorsModelTests extends TestCase
{
    /**
     * Тестирует свойства ColorsModel
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ColorsModel::class);
        
        $this->assertTrue($reflection->hasConstant('DELETE'));
        $this->assertTrue($reflection->hasConstant('CREATE'));
        
        $model = new ColorsModel();
        
        $this->assertArrayHasKey('id', $model->attributes);
        $this->assertArrayHasKey('color', $model->attributes);
    }
    
    /**
     * Тестирует метод ColorsModel::tableName
     */
    public function testTableName()
    {
        $result = ColorsModel::tableName();
        
        $this->assertSame('colors', $result);
    }
    
    /**
     * Тестирует метод ColorsModel::scenarios
     */
    public function testScenarios()
    {
        $model = new ColorsModel(['scenario'=>ColorsModel::DELETE]);
        $model->attributes = [
            'id'=>23
        ];
        
        $this->assertEquals(23, $model->id);
        
        $model = new ColorsModel(['scenario'=>ColorsModel::CREATE]);
        $model->attributes = [
            'color'=>'color'
        ];
        
        $this->assertEquals('color', $model->color);
    }
    
    /**
     * Тестирует метод ColorsModel::rules
     */
    public function testRules()
    {
        $model = new ColorsModel(['scenario'=>ColorsModel::DELETE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertCount(1, $model->errors);
        
        $model = new ColorsModel(['scenario'=>ColorsModel::DELETE]);
        $model->attributes = [
            'id'=>23
        ];
        $model->validate();
        
        $this->assertEmpty($model->errors);
        
        $model = new ColorsModel(['scenario'=>ColorsModel::CREATE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertCount(1, $model->errors);
        
        $model = new ColorsModel(['scenario'=>ColorsModel::CREATE]);
        $model->attributes = [
            'color'=>'color'
        ];
        $model->validate();
        
        $this->assertEmpty($model->errors);
    }
}
