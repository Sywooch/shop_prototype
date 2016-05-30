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
        
        $this->assertEmpty($productMapper->objectsArray);
        $this->assertNull($productMapper->objectsOne);
        
        $_GET = ['id'=>1];
        
        $productMapper->visit(new ProductDetailQueryCreator());
        
        $command = \Yii::$app->db->createCommand($productMapper->query);
        $command->bindValue(':' . \Yii::$app->params['idKey'], \Yii::$app->request->get(\Yii::$app->params['idKey']));
        $productMapper->DbArray = $command->queryOne();
        
        $this->assertFalse(empty($productMapper->DbArray));
        
        $productMapper->visit(new ProductDetailObjectsFactory());
        
        $this->assertTrue(isset($productMapper->objectsOne));
        $this->assertTrue(is_object($productMapper->objectsOne));
        $this->assertTrue($productMapper->objectsOne instanceof ProductsModel);
        
        $this->assertTrue(property_exists($productMapper->objectsOne, 'id'));
        $this->assertTrue(property_exists($productMapper->objectsOne, 'code'));
        $this->assertTrue(property_exists($productMapper->objectsOne, 'name'));
        $this->assertTrue(property_exists($productMapper->objectsOne, 'description'));
        $this->assertTrue(property_exists($productMapper->objectsOne, 'price'));
        $this->assertTrue(property_exists($productMapper->objectsOne, 'images'));
        
        $this->assertTrue(isset($productMapper->objectsOne->id));
        $this->assertTrue(isset($productMapper->objectsOne->code));
        $this->assertTrue(isset($productMapper->objectsOne->name));
        $this->assertTrue(isset($productMapper->objectsOne->description));
        $this->assertTrue(isset($productMapper->objectsOne->price));
        $this->assertTrue(isset($productMapper->objectsOne->images));
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
