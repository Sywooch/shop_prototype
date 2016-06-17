<?php

namespace app\tests\mappers;

use app\mappers\ProductDetailMapper;
use app\tests\DbManager;
use app\models\ProductsModel;

/**
 * Тестирует класс app\mappers\ProductDetailMapper
 */
class ProductDetailMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager();
        self::$dbClass->createDbAndData();
    }
    
    /**
     * Тестирует метод ProductDetailMapper::getOne
     */
    public function testGetOne()
    {
        $_GET = ['id'=>1];
        
        $productMapper = new ProductDetailMapper([
            'tableName'=>'products',
            'fields'=>['id', 'code', 'name', 'description', 'price', 'images'],
        ]);
        $objectProductsArray = $productMapper->getGroup();
        
        $this->assertTrue(is_object($objectProductsArray[0]));
        $this->assertTrue($objectProductsArray[0] instanceof ProductsModel);
        
        $this->assertTrue(property_exists($objectProductsArray[0], 'id'));
        $this->assertTrue(property_exists($objectProductsArray[0], 'code'));
        $this->assertTrue(property_exists($objectProductsArray[0], 'name'));
        $this->assertTrue(property_exists($objectProductsArray[0], 'description'));
        $this->assertTrue(property_exists($objectProductsArray[0], 'price'));
        $this->assertTrue(property_exists($objectProductsArray[0], 'images'));
        
        $this->assertTrue(isset($objectProductsArray[0]->id));
        $this->assertTrue(isset($objectProductsArray[0]->code));
        $this->assertTrue(isset($objectProductsArray[0]->name));
        $this->assertTrue(isset($objectProductsArray[0]->description));
        $this->assertTrue(isset($objectProductsArray[0]->price));
        $this->assertTrue(isset($objectProductsArray[0]->images));
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
