<?php

namespace app\tests\factories;

use app\factories\ProductObjectsFactory;
use app\tests\DbManager;
use app\mappers\ProductsListMapper;
use app\queries\ProductListQueryCreator;
use app\models\ProductModel;

/**
 * Тестирует класс app\factories\ProductObjectsFactory
 */
class ProductObjectsFactoryTests extends \PHPUnit_Framework_TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager();
        self::$dbClass->createDbAndData();
    }
    
    /**
     * Тестирует метод ProductObjectsFactory::getObjects()
     */
    public function testGetObjects()
    {
        $productsMapper = new ProductsListMapper([
            'tableName'=>'products',
            'fields'=>['id', 'code', 'name', 'description', 'price', 'images'],
            'orderByField'=>'price'
        ]);
        
        $this->assertEmpty($productsMapper->objectsArray);
        $this->assertEmpty($productsMapper->DbArray);
        
        $_GET = array();
        
        $productsMapper->visit(new ProductListQueryCreator());
        
        $productsMapper->DbArray = \Yii::$app->db->createCommand($productsMapper->query)->queryAll();
        
        $this->assertFalse(empty($productsMapper->DbArray));
        
        $productsMapper->visit(new ProductObjectsFactory());
        
        $this->assertFalse(empty($productsMapper->objectsArray));
        $this->assertTrue(is_object($productsMapper->objectsArray[0]));
        $this->assertTrue($productsMapper->objectsArray[0] instanceof ProductModel);
        
        $this->assertTrue(property_exists($productsMapper->objectsArray[0], 'id'));
        $this->assertTrue(property_exists($productsMapper->objectsArray[1], 'code'));
        $this->assertTrue(property_exists($productsMapper->objectsArray[0], 'name'));
        $this->assertTrue(property_exists($productsMapper->objectsArray[2], 'description'));
        $this->assertTrue(property_exists($productsMapper->objectsArray[0], 'price'));
        $this->assertTrue(property_exists($productsMapper->objectsArray[0], 'images'));
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
