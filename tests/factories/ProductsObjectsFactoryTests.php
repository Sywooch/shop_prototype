<?php

namespace app\tests\factories;

use app\factories\ProductsObjectsFactory;
use app\tests\DbManager;
use app\mappers\ProductsListMapper;
use app\queries\ProductsListQueryCreator;
use app\models\ProductsModel;

/**
 * Тестирует класс app\factories\ProductsObjectsFactory
 */
class ProductsObjectsFactoryTests extends \PHPUnit_Framework_TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager();
        self::$dbClass->createDbAndData();
    }
    
    /**
     * Тестирует метод ProductsObjectsFactory::getObjects()
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
        
        $productsMapper->visit(new ProductsListQueryCreator());
        
        $productsMapper->DbArray = \Yii::$app->db->createCommand($productsMapper->query)->queryAll();
        
        $this->assertFalse(empty($productsMapper->DbArray));
        
        $productsMapper->visit(new ProductsObjectsFactory());
        
        $this->assertFalse(empty($productsMapper->objectsArray));
        $this->assertTrue(is_object($productsMapper->objectsArray[0]));
        $this->assertTrue($productsMapper->objectsArray[0] instanceof ProductsModel);
        
        $this->assertTrue(property_exists($productsMapper->objectsArray[0], 'id'));
        $this->assertTrue(property_exists($productsMapper->objectsArray[0], 'code'));
        $this->assertTrue(property_exists($productsMapper->objectsArray[0], 'name'));
        $this->assertTrue(property_exists($productsMapper->objectsArray[0], 'description'));
        $this->assertTrue(property_exists($productsMapper->objectsArray[0], 'price'));
        $this->assertTrue(property_exists($productsMapper->objectsArray[0], 'images'));
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
