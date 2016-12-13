<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\models\BrandsModel;

/**
 * Тестирует класс BrandsModel
 */
class BrandsModelTests extends TestCase
{
    /**
     * Тестирует свойства BrandsModel
     */
    public function testProperties()
    {
        $model = new BrandsModel();
        
        $this->assertArrayHasKey('id', $model->attributes);
        $this->assertArrayHasKey('brand', $model->attributes);
    }
    
    /**
     * Тестирует метод BrandsModel::tableName
     */
    public function testTableName()
    {
        $result = BrandsModel::tableName();
        
        $this->assertSame('brands', $result);
    }
}
