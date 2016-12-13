<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\models\ColorsModel;

/**
 * Тестирует класс ColorsModel
 */
class ColorsModelTests extends TestCase
{
    /**
     * Тестирует свойства ColorsModel
     */
    public function testProperties()
    {
        $model = new ColorsModel();
        
        $this->assertArrayHasKey('id', $model->attributes);
        $this->assertArrayHasKey('color', $model->attributes);
    }
    
    /**
     * Тестирует метод ColorsModel::tableName
     */
    public function testTableName()
    {
        $result = ColorsModel::tableName();
        
        $this->assertSame('colors', $result);
    }
}
