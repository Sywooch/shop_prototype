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
        $objectProduct = $productMapper->getOne();
        
        $this->assertTrue(is_object($objectProduct));
        $this->assertTrue($objectProduct instanceof ProductsModel);
        
        $this->assertTrue(property_exists($objectProduct, 'id'));
        $this->assertTrue(property_exists($objectProduct, 'code'));
        $this->assertTrue(property_exists($objectProduct, 'name'));
        $this->assertTrue(property_exists($objectProduct, 'description'));
        $this->assertTrue(property_exists($objectProduct, 'price'));
        $this->assertTrue(property_exists($objectProduct, 'images'));
        
        $this->assertTrue(isset($objectProduct->id));
        $this->assertTrue(isset($objectProduct->code));
        $this->assertTrue(isset($objectProduct->name));
        $this->assertTrue(isset($objectProduct->description));
        $this->assertTrue(isset($objectProduct->price));
        $this->assertTrue(isset($objectProduct->images));
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
