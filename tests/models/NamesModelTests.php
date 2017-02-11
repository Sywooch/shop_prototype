<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\models\NamesModel;

/**
 * Тестирует класс NamesModel
 */
class NamesModelTests extends TestCase
{
    /**
     * Тестирует свойства NamesModel
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(NamesModel::class);
        
        $this->assertTrue($reflection->hasConstant('SAVE'));
        
        $model = new NamesModel();
        
        $this->assertArrayHasKey('id', $model->attributes);
        $this->assertArrayHasKey('name', $model->attributes);
    }
    
    /**
     * Тестирует метод NamesModel::tableName
     */
    public function testTableName()
    {
        $result = NamesModel::tableName();
        
        $this->assertSame('names', $result);
    }
    
    /**
     * Тестирует метод NamesModel::rules
     */
    public function testRules()
    {
        $model = new NamesModel(['scenario'=>NamesModel::SAVE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertNotEmpty($model->errors);
        $this->assertCount(1, $model->errors);
        
        $model = new NamesModel(['scenario'=>NamesModel::SAVE]);
        $model->attributes = [
            'name'=>'name'
        ];
        
        $model->validate();
        
        $this->assertEmpty($model->errors);
    }
}
