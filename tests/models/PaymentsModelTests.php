<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\models\PaymentsModel;

/**
 * Тестирует класс PaymentsModel
 */
class PaymentsModelTests extends TestCase
{
    /**
     * Тестирует свойства PaymentsModel
     */
    public function testProperties()
    {
        $model = new PaymentsModel();
        
        $this->assertArrayHasKey('id', $model->attributes);
        $this->assertArrayHasKey('name', $model->attributes);
        $this->assertArrayHasKey('description', $model->attributes);
    }
    
    /**
     * Тестирует метод PaymentsModel::tableName
     */
    public function testTableName()
    {
        $result = PaymentsModel::tableName();
        
        $this->assertSame('payments', $result);
    }
}
