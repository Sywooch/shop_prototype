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
}
