<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\models\ProductsBrandsModel;

/**
 * Тестирует класс app\models\ProductsBrandsModel
 */
class ProductsBrandsModelTests extends TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'products_brands'=>'app\tests\sources\fixtures\ProductsBrandsFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\ProductsBrandsModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\ProductsBrandsModel
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_ADD_PRODUCT'));
        
        $model = new ProductsBrandsModel();
        
        $this->assertTrue(array_key_exists('id_product', $model->attributes));
        $this->assertTrue(array_key_exists('id_brand', $model->attributes));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $fixture = self::$_dbClass->products_brands['product_brand_1'];
        
        $model = new ProductsBrandsModel(['scenario'=>ProductsBrandsModel::GET_FROM_ADD_PRODUCT]);
        $model->attributes = [
            'id_product'=>$fixture['id_product'],
            'id_brand'=>$fixture['id_brand'], 
        ];
        
        $this->assertEquals($fixture['id_product'], $model->id_product);
        $this->assertEquals($fixture['id_brand'], $model->id_brand);
    }
    
     /**
     * Тестирует правила проверки
     */
    public function testRules()
    {
        $fixture = self::$_dbClass->products_brands['product_brand_1'];
        
        $model = new ProductsBrandsModel(['scenario'=>ProductsBrandsModel::GET_FROM_ADD_PRODUCT]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(2, count($model->errors));
        $this->assertTrue(array_key_exists('id_product', $model->errors));
        $this->assertTrue(array_key_exists('id_brand', $model->errors));
        
        $model = new ProductsBrandsModel(['scenario'=>ProductsBrandsModel::GET_FROM_ADD_PRODUCT]);
        $model->attributes = [
            'id_product'=>$fixture['id_product'],
            'id_brand'=>$fixture['id_brand'], 
        ];
        $model->validate();
        
        $this->assertTrue(empty($model->errors));
    }
    
    /**
     * Тестирует запрос на получение массива объектов
     */
    public function testGetAll()
    {
        $colorsQuery = ProductsBrandsModel::find();
        $colorsQuery->extendSelect(['id_product', 'id_brand']);
        
        $queryRaw = clone $colorsQuery;
        
        $expectedQuery = "SELECT `products_brands`.`id_product`, `products_brands`.`id_brand` FROM `products_brands`";
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $colorsQuery->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof ProductsBrandsModel);
    }
    
    /**
     * Тестирует запрос на получение 1 объекта
     */
    public function testGetOne()
    {
        $fixture = self::$_dbClass->products_brands['product_brand_1'];
        
        $colorsQuery = ProductsBrandsModel::find();
        $colorsQuery->extendSelect(['id_product', 'id_brand']);
        $colorsQuery->where(['[[products_brands.id_product]]'=>$fixture['id_product']]);
        
        $queryRaw = clone $colorsQuery;
        
        $expectedQuery = sprintf("SELECT `products_brands`.`id_product`, `products_brands`.`id_brand` FROM `products_brands` WHERE `products_brands`.`id_product`=%d", $fixture['id_product']);
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $colorsQuery->one();
        
        $this->assertTrue($result instanceof ProductsBrandsModel);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
