<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\models\AddressModel;

/**
 * Тестирует класс AddressModel
 */
class AddressModelTests extends TestCase
{
    /**
     * Тестирует свойства AddressModel
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AddressModel::class);
        
        $this->assertTrue($reflection->hasConstant('SAVE'));
        
        $model = new AddressModel();
        
        $this->assertArrayHasKey('id', $model->attributes);
        $this->assertArrayHasKey('address', $model->attributes);
    }
    
    /**
     * Тестирует метод AddressModel::tableName
     */
    public function testTableName()
    {
        $result = AddressModel::tableName();
        
        $this->assertSame('address', $result);
    }
    
    /**
     * Тестирует метод AddressModel::rules
     */
    public function testRules()
    {
        $model = new AddressModel(['scenario'=>AddressModel::SAVE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertNotEmpty($model->errors);
        $this->assertCount(1, $model->errors);
        
        $model = new AddressModel(['scenario'=>AddressModel::SAVE]);
        $model->attributes = [
            'address'=>'address'
        ];
        
        $model->validate();
        
        $this->assertEmpty($model->errors);
    }
}
