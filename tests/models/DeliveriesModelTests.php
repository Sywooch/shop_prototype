<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\models\DeliveriesModel;

/**
 * Тестирует класс DeliveriesModel
 */
class DeliveriesModelTests extends TestCase
{
    /**
     * Тестирует свойства DeliveriesModel
     */
    public function testProperties()
    {
        $model = new DeliveriesModel();
        
        $this->assertArrayHasKey('id', $model->attributes);
        $this->assertArrayHasKey('name', $model->attributes);
        $this->assertArrayHasKey('description', $model->attributes);
        $this->assertArrayHasKey('price', $model->attributes);
    }
    
    /**
     * Тестирует метод DeliveriesModel::tableName
     */
    public function testTableName()
    {
        $result = DeliveriesModel::tableName();
        
        $this->assertSame('deliveries', $result);
    }
}
