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
            'orderByField'=>'date'
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
        
        $this->assertTrue(isset($productsList[0]->id));
        $this->assertTrue(isset($productsList[0]->code));
        $this->assertTrue(isset($productsList[0]->name));
        $this->assertTrue(isset($productsList[0]->description));
        $this->assertTrue(isset($productsList[0]->price));
        $this->assertTrue(isset($productsList[0]->images));
    }
    
    /**
     * Тестирую утверждение, что при передаче в $_GET типа сортировки, значение свойства ProductsListMapper::orderByType изменяется
     */
    public function testOrderByType()
    {
        $config = [
            'tableName'=>'products',
            'fields'=>['id', 'code', 'name', 'description', 'price', 'images'],
            'orderByField'=>'date'
        ];
        
        $productsMapper = new ProductsListMapper($config);
        
        $this->assertEquals(\Yii::$app->params['orderByType'], $productsMapper->orderByType);
        
        $_GET = [\Yii::$app->params['orderTypePointer']=>'ASC'];
        
        $productsMapper = new ProductsListMapper($config);
        
        $this->assertEquals('ASC', $productsMapper->orderByType);
    }
    
    /**
     * Тестирую утверждение, что при передаче в $_GET поля сортировки, значение свойства ProductsListMapper::orderByField изменяется
     */
    public function testOrderByField()
    {
        $config = [
            'tableName'=>'products',
            'fields'=>['id', 'code', 'name', 'description', 'price', 'images'],
            'orderByField'=>'date'
        ];
        
        $productsMapper = new ProductsListMapper($config);
        
        $this->assertEquals('date', $productsMapper->orderByField);
        
        $_GET = [\Yii::$app->params['orderFieldPointer']=>'price'];
        
        $productsMapper = new ProductsListMapper($config);
        
        $this->assertEquals('price', $productsMapper->orderByField);
    }
     
    /**
     * Тестирую возможномть изменения значения свойства ProductsListMapper::queryClass
     */
    public function testQueryClass()
    {
        $config = [
            'tableName'=>'products',
            'fields'=>['id', 'code', 'name', 'description', 'price', 'images'],
            'orderByField'=>'date'
        ];
        
        $productsMapper = new ProductsListMapper($config);
        
        $this->assertEquals('app\queries\ProductsListQueryCreator', $productsMapper->queryClass);
        
        $config['queryClass'] = 'app\queries\ProductsListSearchQueryCreator';
        
        $productsMapper = new ProductsListMapper($config);
        
        $this->assertEquals('app\queries\ProductsListSearchQueryCreator', $productsMapper->queryClass);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
