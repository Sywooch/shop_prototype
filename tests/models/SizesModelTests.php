<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\models\SizesModel;

/**
 * Тестирует класс SizesModel
 */
class SizesModelTests extends TestCase
{
    /**
     * Тестирует свойства SizesModel
     */
    public function testProperties()
    {
        $model = new SizesModel();
        
        $this->assertArrayHasKey('id', $model->attributes);
        $this->assertArrayHasKey('size', $model->attributes);
    }
    
    /**
     * Тестирует метод SizesModel::tableName
     */
    public function testTableName()
    {
        $result = SizesModel::tableName();
        
        $this->assertSame('sizes', $result);
    }
}
