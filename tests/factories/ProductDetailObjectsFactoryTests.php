<?php

namespace app\tests\factories;

use app\factories\ProductDetailObjectsFactory;
use app\tests\DbManager;
use app\mappers\ProductDetailMapper;
use app\queries\ProductDetailQueryCreator;
use app\models\ProductsModel;

/**
 * Тестирует класс app\factories\ProductDetailObjectsFactory
 */
class ProductDetailObjectsFactoryTests extends \PHPUnit_Framework_TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager();
        self::$dbClass->createDbAndData();
    }
    
    /**
     * Тестирует метод ProductDetailObjectsFactory::getObjects()
     */
    public function testGetObjects()
    {
        $productMapper = new ProductDetailMapper([
            'tableName'=>'products',
            'fields'=>['id', 'code', 'name', 'description', 'price', 'images'],
        ]);
        
        $this->assertEmpty($productMapper->DbArray);
        $this->assertEmpty($productMapper->objectsArray);
        
        $_GET = ['id'=>1];
        
        $productMapper->visit(new ProductDetailQueryCreator());
        
        $command = \Yii::$app->db->createCommand($productMapper->query);
        $command->bindValues([':' . \Yii::$app->params['idKey']=>\Yii::$app->request->get(\Yii::$app->params['idKey'])]);
        $productMapper->DbArray = $command->queryAll();
        
        $this->assertFalse(empty($productMapper->DbArray));
        
        $productMapper->visit(new ProductDetailObjectsFactory());
        
        $this->assertFalse(empty($productMapper->objectsArray));
        $this->assertTrue(is_object($productMapper->objectsArray[0]));
        $this->assertTrue($productMapper->objectsArray[0] instanceof ProductsModel);
        
        $this->assertTrue(property_exists($productMapper->objectsArray[0], 'id'));
        $this->assertTrue(property_exists($productMapper->objectsArray[0], 'code'));
        $this->assertTrue(property_exists($productMapper->objectsArray[0], 'name'));
        $this->assertTrue(property_exists($productMapper->objectsArray[0], 'description'));
        $this->assertTrue(property_exists($productMapper->objectsArray[0], 'price'));
        $this->assertTrue(property_exists($productMapper->objectsArray[0], 'images'));
        
        $this->assertTrue(isset($productMapper->objectsArray[0]->id));
        $this->assertTrue(isset($productMapper->objectsArray[0]->code));
        $this->assertTrue(isset($productMapper->objectsArray[0]->name));
        $this->assertTrue(isset($productMapper->objectsArray[0]->description));
        $this->assertTrue(isset($productMapper->objectsArray[0]->price));
        $this->assertTrue(isset($productMapper->objectsArray[0]->images));
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
