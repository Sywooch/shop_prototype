<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\models\SurnamesModel;

/**
 * Тестирует класс SurnamesModel
 */
class SurnamesModelTests extends TestCase
{
    /**
     * Тестирует свойства SurnamesModel
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(SurnamesModel::class);
        
        $this->assertTrue($reflection->hasConstant('SAVE'));
        
        $model = new SurnamesModel();
        
        $this->assertArrayHasKey('id', $model->attributes);
        $this->assertArrayHasKey('surname', $model->attributes);
    }
    
    /**
     * Тестирует метод SurnamesModel::tableName
     */
    public function testTableName()
    {
        $result = SurnamesModel::tableName();
        
        $this->assertSame('surnames', $result);
    }
    
    /**
     * Тестирует метод SurnamesModel::rules
     */
    public function testRules()
    {
        $model = new SurnamesModel(['scenario'=>SurnamesModel::SAVE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertNotEmpty($model->errors);
        $this->assertCount(1, $model->errors);
        
        $model = new SurnamesModel(['scenario'=>SurnamesModel::SAVE]);
        $model->attributes = [
            'surname'=>'surname'
        ];
        
        $model->validate();
        
        $this->assertEmpty($model->errors);
    }
}
