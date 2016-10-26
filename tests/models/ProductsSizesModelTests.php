<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\models\{ProductsModel,
    ProductsSizesModel,
    SizesModel};

/**
 * Тестирует класс app\models\ProductsSizesModel
 */
class ProductsSizesModelTests extends TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'products_sizes'=>'app\tests\sources\fixtures\ProductsSizesFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\ProductsSizesModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\ProductsSizesModel
     */
    public function testProperties()
    {
        $model = new ProductsSizesModel();
        
        $this->assertTrue(array_key_exists('id_product', $model->attributes));
        $this->assertTrue(array_key_exists('id_size', $model->attributes));
    }
    
    /**
     * Тестирует запрос на получение массива объектов
     */
    public function testGetAll()
    {
        $productsSizesQuery = ProductsSizesModel::find();
        $productsSizesQuery->extendSelect(['id_product', 'id_size']);
        
        $queryRaw = clone $productsSizesQuery;
        
        $expectedQuery = "SELECT `products_sizes`.`id_product`, `products_sizes`.`id_size` FROM `products_sizes`";
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $productsSizesQuery->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof ProductsSizesModel);
    }
    
    /**
     * Тестирует запрос на получение 1 объекта
     */
    public function testGetOne()
    {
        $fixture = self::$_dbClass->products_sizes['product_size_1'];
        
        $productsSizesQuery = ProductsSizesModel::find();
        $productsSizesQuery->extendSelect(['id_product', 'id_size']);
        $productsSizesQuery->where(['products_sizes.id_product'=>$fixture['id_product']]);
        
        $queryRaw = clone $productsSizesQuery;
        
        $expectedQuery = sprintf("SELECT `products_sizes`.`id_product`, `products_sizes`.`id_size` FROM `products_sizes` WHERE `products_sizes`.`id_product`=%d", $fixture['id_product']);
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $productsSizesQuery->one();
        
        $this->assertTrue($result instanceof ProductsSizesModel);
    }
    
    /**
     * Тестирует метод ExtendActiveQuery::allMap
     */
    public function testAllMap()
    {
        $fixture = self::$_dbClass->products_sizes['product_size_1'];
        $fixture2 = self::$_dbClass->products_sizes['product_size_2'];
        
        $productsSizesQuery = ProductsSizesModel::find();
        $productsSizesQuery->extendSelect(['id_product', 'id_size']);
        $productsSizesArray = $productsSizesQuery->allMap('id_product', 'id_size');
        
        $this->assertFalse(empty($productsSizesArray));
        $this->assertTrue(array_key_exists($fixture['id_product'], $productsSizesArray));
        $this->assertTrue(array_key_exists($fixture2['id_product'], $productsSizesArray));
        $this->assertTrue(in_array($fixture['id_size'], $productsSizesArray));
        $this->assertTrue(in_array($fixture2['id_size'], $productsSizesArray));
    }
    
    /**
     * Тестирует метод ProductsSizesModel::batchInsert
     */
    public function testBatchInsert()
    {
        $fixture_1 = self::$_dbClass->products_sizes['product_size_1'];
        $fixture_2 = self::$_dbClass->products_sizes['product_size_2'];
        
        $productsModel = new ProductsModel(['id'=>$fixture_1['id_product']]);
        $sizesModel = new SizesModel(['id'=>[$fixture_1['id_size'], $fixture_2['id_size']]]);
        
        \Yii::$app->db->createCommand('DELETE FROM {{products_sizes}}')->execute();
        $this->assertTrue(empty(\Yii::$app->db->createCommand('SELECT * FROM {{products_sizes}}')->queryAll()));
        
        $result = ProductsSizesModel::batchInsert($productsModel, $sizesModel);
        $this->assertTrue(is_int($result));
        $this->assertEquals(2, $result);
        
        $this->assertFalse(empty($result = \Yii::$app->db->createCommand('SELECT * FROM {{products_sizes}}')->queryAll()));
        $this->assertEquals(2, count($result));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
