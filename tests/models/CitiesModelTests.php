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
}
