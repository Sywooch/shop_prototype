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
        $reflection = new \ReflectionClass(RelatedProductsModel::class);
        
        $this->assertTrue($reflection->hasConstant('SAVE'));
        $this->assertTrue($reflection->hasConstant('DELETE'));
        
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
    
    /**
     * Тестирует метод RelatedProductsModel::scenarios
     */
    public function testScenarios()
    {
        $model = new RelatedProductsModel(['scenario'=>RelatedProductsModel::SAVE]);
        $model->attributes = [
            'id_product'=>12,
            'id_related_product'=>2
        ];
        
        $this->assertEquals(12, $model->id_product);
        $this->assertEquals(2, $model->id_related_product);
        
        $model = new RelatedProductsModel(['scenario'=>RelatedProductsModel::DELETE]);
        $model->attributes = [
            'id_product'=>12,
        ];
        
        $this->assertEquals(12, $model->id_product);
    }
    
    /**
     * Тестирует метод RelatedProductsModel::rules
     */
    public function testRules()
    {
        $model = new RelatedProductsModel(['scenario'=>RelatedProductsModel::SAVE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertCount(2, $model->errors);
        
        $model = new RelatedProductsModel(['scenario'=>RelatedProductsModel::SAVE]);
        $model->attributes = [
            'id_product'=>12,
            'id_related_product'=>2
        ];
        $model->validate();
        
        $this->assertEmpty($model->errors);
        
        $model = new RelatedProductsModel(['scenario'=>RelatedProductsModel::DELETE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertCount(1, $model->errors);
        
        $model = new RelatedProductsModel(['scenario'=>RelatedProductsModel::DELETE]);
        $model->attributes = [
            'id_product'=>12,
        ];
        $model->validate();
        
        $this->assertEmpty($model->errors);
    }
}
