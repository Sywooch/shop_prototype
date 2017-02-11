<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\models\PhonesModel;

/**
 * Тестирует класс PhonesModel
 */
class PhonesModelTests extends TestCase
{
    /**
     * Тестирует свойства PhonesModel
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(PhonesModel::class);
        
        $this->assertTrue($reflection->hasConstant('SAVE'));
        
        $model = new PhonesModel();
        
        $this->assertArrayHasKey('id', $model->attributes);
        $this->assertArrayHasKey('phone', $model->attributes);
    }
    
    /**
     * Тестирует метод PhonesModel::tableName
     */
    public function testTableName()
    {
        $result = PhonesModel::tableName();
        
        $this->assertSame('phones', $result);
    }
    
    /**
     * Тестирует метод PhonesModel::rules
     */
    public function testRules()
    {
        $model = new PhonesModel(['scenario'=>PhonesModel::SAVE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertNotEmpty($model->errors);
        $this->assertCount(1, $model->errors);
        
        $model = new PhonesModel(['scenario'=>PhonesModel::SAVE]);
        $model->attributes = [
            'phone'=>'phone'
        ];
        
        $model->validate();
        
        $this->assertEmpty($model->errors);
    }
}
