<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\models\CountriesModel;

/**
 * Тестирует класс CountriesModel
 */
class CountriesModelTests extends TestCase
{
    /**
     * Тестирует свойства CountriesModel
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(CountriesModel::class);
        
        $this->assertTrue($reflection->hasConstant('SAVE'));
        
        $model = new CountriesModel();
        
        $this->assertArrayHasKey('id', $model->attributes);
        $this->assertArrayHasKey('country', $model->attributes);
    }
    
    /**
     * Тестирует метод CountriesModel::tableName
     */
    public function testTableName()
    {
        $result = CountriesModel::tableName();
        
        $this->assertSame('countries', $result);
    }
    
    /**
     * Тестирует метод CountriesModel::rules
     */
    public function testRules()
    {
        $model = new CountriesModel(['scenario'=>CountriesModel::SAVE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertNotEmpty($model->errors);
        $this->assertCount(1, $model->errors);
        
        $model = new CountriesModel(['scenario'=>CountriesModel::SAVE]);
        $model->attributes = [
            'country'=>'country'
        ];
        
        $model->validate();
        
        $this->assertEmpty($model->errors);
    }
}
