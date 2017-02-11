<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\models\CitiesModel;

/**
 * Тестирует класс CitiesModel
 */
class CitiesModelTests extends TestCase
{
    /**
     * Тестирует свойства CitiesModel
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CitiesModel::class);
        
        $this->assertTrue($reflection->hasConstant('SAVE'));
        
        $model = new CitiesModel();
        
        $this->assertArrayHasKey('id', $model->attributes);
        $this->assertArrayHasKey('city', $model->attributes);
    }
    
    /**
     * Тестирует метод CitiesModel::tableName
     */
    public function testTableName()
    {
        $result = CitiesModel::tableName();
        
        $this->assertSame('cities', $result);
    }
    
    /**
     * Тестирует метод CitiesModel::rules
     */
    public function testRules()
    {
        $model = new CitiesModel(['scenario'=>CitiesModel::SAVE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertNotEmpty($model->errors);
        $this->assertCount(1, $model->errors);
        
        $model = new CitiesModel(['scenario'=>CitiesModel::SAVE]);
        $model->attributes = [
            'city'=>'city'
        ];
        
        $model->validate();
        
        $this->assertEmpty($model->errors);
    }
}
