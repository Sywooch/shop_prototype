<?php

namespace app\tests\mappers;

use app\mappers\ProductsListMapper;
use app\tests\DbManager;
use app\models\ProductsModel;

/**
 * Тестирует класс app\mappers\ProductsListMapper
 */
class ProductsListMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager();
        self::$dbClass->createDbAndData();
    }
    
    /**
     * Тестирует метод ProductsListMapper::getGroup
     */
    public function testGetGroup()
    {
        $productsMapper = new ProductsListMapper([
            'tableName'=>'products',
            'fields'=>['id', 'code', 'name', 'description', 'price', 'images'],
            'orderByField'=>'price'
        ]);
        $productsList = $productsMapper->getGroup();
        
        $this->assertTrue(is_array($productsList));
        $this->assertFalse(empty($productsList));
        $this->assertTrue(is_object($productsList[0]));
        $this->assertTrue($productsList[0] instanceof ProductsModel);
        
        $this->assertTrue(property_exists($productsList[0], 'id'));
        $this->assertTrue(property_exists($productsList[0], 'code'));
        $this->assertTrue(property_exists($productsList[0], 'name'));
        $this->assertTrue(property_exists($productsList[0], 'description'));
        $this->assertTrue(property_exists($productsList[0], 'price'));
        $this->assertTrue(property_exists($productsList[0], 'images'));
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
