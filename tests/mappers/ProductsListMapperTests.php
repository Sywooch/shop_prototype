<?php

namespace app\tests\mappers;

use app\tests\DbManager;
use app\mappers\ProductsListMapper;
use app\models\ProductsModel;
use app\helpers\MappersHelper;

/**
 * Тестирует класс app\mappers\ProductsListMapper
 */
class ProductsListMapperTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_id = 1;
    private static $_date = 1462453595;
    private static $_code = 'YU-6709';
    private static $_name = 'name';
    private static $_description = 'description';
    private static $_price = 14.45;
    private static $_images = 'images';
    private static $_categorySeocode = 'mensfootwear';
    private static $_subcategorySeocode = 'boots';
    private static $_config = [
        'tableName'=>'products',
        'fields'=>['id', 'date', 'code', 'name', 'description', 'short_description', 'price', 'images'],
        'otherTablesFields'=>[
            ['table'=>'categories', 'fields'=>[['field'=>'seocode', 'as'=>'categories']]],
            ['table'=>'subcategory', 'fields'=>[['field'=>'seocode', 'as'=>'subcategory']]],
        ],
        'orderByField'=>'date'
    ];
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{categories}} SET [[id]]=:id, [[name]]=:name, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':seocode'=>self::$_categorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{subcategory}} SET [[id]]=:id, [[name]]=:name, [[id_categories]]=:id_categories, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':id_categories'=>self::$_id, ':seocode'=>self::$_subcategorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products}} SET [[id]]=:id, [[date]]=:date, [[code]]=:code, [[name]]=:name, [[description]]=:description, [[short_description]]=:short_description, [[price]]=:price, [[images]]=:images, [[id_categories]]=:id_categories, [[id_subcategory]]=:id_subcategory');
        $command->bindValues([':id'=>self::$_id, ':date'=>self::$_date, ':code'=>self::$_code, ':name'=>self::$_name, ':description'=>self::$_description, ':short_description'=>self::$_description, ':price'=>self::$_price, ':images'=>self::$_images, ':id_categories'=>self::$_id, ':id_subcategory'=>self::$_id]);
        $command->execute();
        
        if (!empty(MappersHelper::getObjectRegistry())) {
            MappersHelper::cleanProperties();
        }
    }
    
    /**
     * Тестирует метод ProductsListMapper::getGroup
     */
    public function testGetGroup()
    {
        \Yii::configure(\Yii::$app->filters, ['colors'=>[], 'sizes'=>[], 'brands'=>[]]);
        
        $productsMapper = new ProductsListMapper(self::$_config);
        $productsList = $productsMapper->getGroup();
        
        $this->assertTrue(is_array($productsList));
        $this->assertFalse(empty($productsList));
        $this->assertTrue(is_object($productsList[0]));
        $this->assertTrue($productsList[0] instanceof ProductsModel);
        
        $this->assertTrue(property_exists($productsList[0], 'code'));
        $this->assertTrue(property_exists($productsList[0], 'name'));
        $this->assertTrue(property_exists($productsList[0], 'description'));
        $this->assertTrue(property_exists($productsList[0], 'short_description'));
        $this->assertTrue(property_exists($productsList[0], 'price'));
        $this->assertTrue(property_exists($productsList[0], 'images'));
        
        $this->assertFalse(empty($productsList[0]->id));
        $this->assertFalse(empty($productsList[0]->code));
        $this->assertFalse(empty($productsList[0]->name));
        $this->assertFalse(empty($productsList[0]->description));
        $this->assertFalse(empty($productsList[0]->short_description));
        $this->assertFalse(empty($productsList[0]->price));
        $this->assertFalse(empty($productsList[0]->images));
    }
    
    /**
     * Тестирую утверждение, что при передаче в $_GET типа сортировки, значение свойства ProductsListMapper::orderByType изменяется
     */
    public function testOrderByType()
    {
        $productsMapper = new ProductsListMapper(self::$_config);
        
        \Yii::$app->filters->sortingType = 'ASC';
        
        $productsMapper = new ProductsListMapper(self::$_config);
        
        $this->assertEquals('ASC', $productsMapper->orderByType);
    }
    
    /**
     * Тестирую утверждение, что при передаче в $_GET поля сортировки, значение свойства ProductsListMapper::orderByField изменяется
     */
    public function testOrderByField()
    {
        $productsMapper = new ProductsListMapper(self::$_config);
        
        $this->assertEquals('date', $productsMapper->orderByField);
        
        \Yii::$app->filters->sortingField = 'price';
        
        $productsMapper = new ProductsListMapper(self::$_config);
        
        $this->assertEquals('price', $productsMapper->orderByField);
    }
     
    /**
     * Тестирую возможномть изменения значения свойства ProductsListMapper::queryClass
     */
    public function testQueryClass()
    {
        $productsMapper = new ProductsListMapper(self::$_config);
        
        $this->assertEquals('app\queries\ProductsListQueryCreator', $productsMapper->queryClass);
        
        self::$_config['queryClass'] = 'app\queries\ProductsListSearchQueryCreator';
        
        $productsMapper = new ProductsListMapper(self::$_config);
        
        $this->assertEquals('app\queries\ProductsListSearchQueryCreator', $productsMapper->queryClass);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
