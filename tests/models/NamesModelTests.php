<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\models\NamesModel;

/**
 * Тестирует класс NamesModel
 */
class NamesModelTests extends TestCase
{
    /**
     * Тестирует свойства NamesModel
     */
    public function testProperties()
    {
        $model = new NamesModel();
        
        $this->assertArrayHasKey('id', $model->attributes);
        $this->assertArrayHasKey('name', $model->attributes);
    }
    
    /**
     * Тестирует метод NamesModel::tableName
     */
    public function testTableName()
    {
        $result = NamesModel::tableName();
        
        $this->assertSame('names', $result);
    }
}
