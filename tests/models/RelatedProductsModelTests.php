<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\models\RelatedProductsModel;

/**
 * Тестирует класс RelatedProductsModel
 */
class RelatedProductsModelTests extends TestCase
{
    /**
     * Тестирует свойства RelatedProductsModel
     */
    public function testProperties()
    {
        $model = new RelatedProductsModel();
        
        $this->assertArrayHasKey('id_product', $model->attributes);
        $this->assertArrayHasKey('id_related_product', $model->attributes);
    }
    
    /**
     * Тестирует метод RelatedProductsModel::tableName
     */
    public function testTableName()
    {
        $result = RelatedProductsModel::tableName();
        
        $this->assertSame('related_products', $result);
    }
}
