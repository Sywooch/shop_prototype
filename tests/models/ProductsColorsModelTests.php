<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\models\ProductsColorsModel;

/**
 * Тестирует класс app\models\ProductsColorsModel
 */
class ProductsColorsModelTests extends TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'products_colors'=>'app\tests\sources\fixtures\ProductsColorsFixture',
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\ProductsColorsModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\ProductsColorsModel
     */
    public function testProperties()
    {
        $model = new ProductsColorsModel();
        
        $this->assertTrue(array_key_exists('id_product', $model->attributes));
        $this->assertTrue(array_key_exists('id_color', $model->attributes));
    }
    
    /**
     * Тестирует запрос на получение массива объектов
     */
    public function testGetAll()
    {
        $productsColorsQuery = ProductsColorsModel::find();
        $productsColorsQuery->extendSelect(['id_product', 'id_color']);
        
        $queryRaw = clone $productsColorsQuery;
        
        $expectedQuery = "SELECT `products_colors`.`id_product`, `products_colors`.`id_color` FROM `products_colors`";
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $productsColorsQuery->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof ProductsColorsModel);
    }
    
    /**
     * Тестирует запрос на получение 1 объекта
     */
    public function testGetOne()
    {
        $fixture = self::$_dbClass->products_colors['product_color_1'];
        
        $productsColorsQuery = ProductsColorsModel::find();
        $productsColorsQuery->extendSelect(['id_product', 'id_color']);
        $productsColorsQuery->where(['products_colors.id_product'=>$fixture['id_product']]);
        
        $queryRaw = clone $productsColorsQuery;
        
        $expectedQuery = sprintf("SELECT `products_colors`.`id_product`, `products_colors`.`id_color` FROM `products_colors` WHERE `products_colors`.`id_product`=%d", $fixture['id_product']);
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $productsColorsQuery->one();
        
        $this->assertTrue($result instanceof ProductsColorsModel);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
