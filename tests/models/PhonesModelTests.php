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
}
