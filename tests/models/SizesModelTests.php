<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\models\SizesModel;

/**
 * Тестирует класс SizesModel
 */
class SizesModelTests extends TestCase
{
    /**
     * Тестирует свойства SizesModel
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(SizesModel::class);
        
        $this->assertTrue($reflection->hasConstant('DELETE'));
        $this->assertTrue($reflection->hasConstant('CREATE'));
        
        $model = new SizesModel();
        
        $this->assertArrayHasKey('id', $model->attributes);
        $this->assertArrayHasKey('size', $model->attributes);
    }
    
    /**
     * Тестирует метод SizesModel::tableName
     */
    public function testTableName()
    {
        $result = SizesModel::tableName();
        
        $this->assertSame('sizes', $result);
    }
    
    /**
     * Тестирует метод SizesModel::scenarios
     */
    public function testScenarios()
    {
        $model = new SizesModel(['scenario'=>SizesModel::DELETE]);
        $model->attributes = [
            'id'=>23
        ];
        
        $this->assertEquals(23, $model->id);
        
        $model = new SizesModel(['scenario'=>SizesModel::CREATE]);
        $model->attributes = [
            'size'=>'size'
        ];
        
        $this->assertEquals('size', $model->size);
    }
    
    /**
     * Тестирует метод SizesModel::rules
     */
    public function testRules()
    {
        $model = new SizesModel(['scenario'=>SizesModel::DELETE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertCount(1, $model->errors);
        
        $model = new SizesModel(['scenario'=>SizesModel::DELETE]);
        $model->attributes = [
            'id'=>23
        ];
        $model->validate();
        
        $this->assertEmpty($model->errors);
        
        $model = new SizesModel(['scenario'=>SizesModel::CREATE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertCount(1, $model->errors);
        
        $model = new SizesModel(['scenario'=>SizesModel::CREATE]);
        $model->attributes = [
            'size'=>'size'
        ];
        $model->validate();
        
        $this->assertEmpty($model->errors);
    }
}
