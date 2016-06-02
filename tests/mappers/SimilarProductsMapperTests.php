<?php

namespace app\tests\mappers;

use app\mappers\SimilarProductsMapper;
use app\mappers\ProductDetailMapper;
use app\tests\DbManager;
use app\models\ProductsModel;

/**
 * Тестирует класс app\mappers\SimilarProductsMapper
 */
class SimilarProductsMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager();
        self::$dbClass->createDbAndData();
    }
    
    /**
     * Тестирует метод SimilarProductsMapper::getGroup
     */
    public function testGetGroup()
    {
        $_GET = ['id'=>1, 'categories'=>'mensfootwear', 'subcategory'=>'boots'];
        
        $productMapper = new ProductDetailMapper([
            'tableName'=>'products',
            'fields'=>['id', 'code', 'name', 'description', 'price', 'images'],
        ]);
        $objectProduct = $productMapper->getOne();
        
        $similarProductsMapper = new SimilarProductsMapper([
            'tableName'=>'products',
            'fields'=>['id', 'name', 'price', 'images'],
            'otherTablesFields'=>[
                ['table'=>'categories', 'fields'=>[['field'=>'seocode', 'as'=>'categories']]],
                ['table'=>'subcategory', 'fields'=>[['field'=>'seocode', 'as'=>'subcategory']]],
            ],
            'orderByField'=>'date',
            'model'=>$objectProduct,
        ]);
        $productsList = $similarProductsMapper->getGroup();
        
        $this->assertTrue(is_array($productsList));
        $this->assertFalse(empty($productsList));
        $this->assertTrue(is_object($productsList[0]));
        $this->assertTrue($productsList[0] instanceof ProductsModel);
        
        $this->assertTrue(property_exists($productsList[0], 'id'));
        $this->assertTrue(property_exists($productsList[0], 'name'));
        $this->assertTrue(property_exists($productsList[0], 'price'));
        $this->assertTrue(property_exists($productsList[0], 'images'));
        
        $this->assertTrue(isset($productsList[0]->id));
        $this->assertTrue(isset($productsList[0]->name));
        $this->assertTrue(isset($productsList[0]->price));
        $this->assertTrue(isset($productsList[0]->images));
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->deleteDb();
    }
}
