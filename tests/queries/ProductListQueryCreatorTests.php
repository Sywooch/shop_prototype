<?php

namespace app\tests\queries;

use app\tests\DbManager;
use app\queries\ProductListQueryCreator;
use app\mappers\ProductsListMapper;

/**
 * Тестирует класс ProductListQueryCreator
 */
class ProductListQueryCreatorTests extends \PHPUnit_Framework_TestCase
{
    private static $dbClass;
    private static $productsListMapper;
    private static $productListQueryCreator;
    
    public static function SetUpBeforeClass()
    {
        self::$dbClass = new DbManager();
        self::$dbClass->createDbAndData();
        
        self::$productsListMapper = new ProductsListMapper(['tableName'=>'products', 'fields'=>['id', 'name', 'description', 'price'], 'orderByField'=>'price']);
        self::$productListQueryCreator = new ProductListQueryCreator();
    }
    
    /**
     * Тестирует создание строки SQL запроса без категорий и фильтров ProductListQueryCreator::queryForAll()
     */
    public function testQueryForAll()
    {
        self::$productsListMapper->visit(self::$productListQueryCreator);
        
        $query = 'SELECT [[products.id]],[[products.name]],[[products.description]],[[products.price]] FROM {{products}} ORDER BY [[products.price]] DESC LIMIT 0, 20';
        $this->assertEquals($query, self::$productsListMapper->query);
    }
    
    /**
     * Тестирует создание строки SQL c выборкой по категории ProductListQueryCreator::queryForCategory()
     */
    public function testQueryForCategory()
    {
        $_GET['categories']='мужская%20одежда';
        
        self::$productsListMapper->visit(self::$productListQueryCreator);
        
        $query = 'SELECT [[products.id]],[[products.name]],[[products.description]],[[products.price]] FROM {{products}} JOIN {{categories}} ON [[products.id_category]]=[[categories.id]] WHERE [[categories.name]]=:categories ORDER BY [[products.price]] DESC LIMIT 0, 20';
        $this->assertEquals($query, self::$productsListMapper->query);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
