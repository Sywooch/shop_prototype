<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\models\SurnamesModel;

/**
 * Тестирует класс SurnamesModel
 */
class SurnamesModelTests extends TestCase
{
    /**
     * Тестирует свойства SurnamesModel
     */
    public function testProperties()
    {
        $model = new SurnamesModel();
        
        $this->assertArrayHasKey('id', $model->attributes);
        $this->assertArrayHasKey('surname', $model->attributes);
    }
    
    /**
     * Тестирует метод SurnamesModel::tableName
     */
    public function testTableName()
    {
        $result = SurnamesModel::tableName();
        
        $this->assertSame('surnames', $result);
    }
}
