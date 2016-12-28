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
}
