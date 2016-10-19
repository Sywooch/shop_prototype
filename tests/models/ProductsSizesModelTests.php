<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\models\ProductsSizesModel;

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
        
        $productssizesQuery = ProductsSizesModel::find();
        $productssizesQuery->extendSelect(['id_product', 'id_size']);
        $productssizesQuery->where(['products_sizes.id_product'=>$fixture['id_product']]);
        
        $queryRaw = clone $productssizesQuery;
        
        $expectedQuery = sprintf("SELECT `products_sizes`.`id_product`, `products_sizes`.`id_size` FROM `products_sizes` WHERE `products_sizes`.`id_product`=%d", $fixture['id_product']);
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $productssizesQuery->one();
        
        $this->assertTrue($result instanceof ProductsSizesModel);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
