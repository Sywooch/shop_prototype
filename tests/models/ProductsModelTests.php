<?php

namespace app\tests\models;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\tests\source\fixtures\ProductsFixture;
use app\models\{CategoriesModel,
    ProductsModel,
    SubcategoryModel};

/**
 * Тестирует класс app\models\ProductsModel
 */
class ProductsModelTests extends TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::className(),
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\ProductsModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\ProductsModel
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_FORM'));
        
        $this->assertTrue(self::$_reflectionClass->hasProperty('_tableName'));
        
        $model = new ProductsModel();
        
        $this->assertTrue(array_key_exists('id', $model->attributes));
        $this->assertTrue(array_key_exists('date', $model->attributes));
        $this->assertTrue(array_key_exists('code', $model->attributes));
        $this->assertTrue(array_key_exists('name', $model->attributes));
        $this->assertTrue(array_key_exists('description', $model->attributes));
        $this->assertTrue(array_key_exists('short_description', $model->attributes));
        $this->assertTrue(array_key_exists('price', $model->attributes));
        $this->assertTrue(array_key_exists('images', $model->attributes));
        $this->assertTrue(array_key_exists('id_category', $model->attributes));
        $this->assertTrue(array_key_exists('id_subcategory', $model->attributes));
        $this->assertTrue(array_key_exists('active', $model->attributes));
        $this->assertTrue(array_key_exists('total_products', $model->attributes));
        $this->assertTrue(array_key_exists('seocode', $model->attributes));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $fixture = self::$_dbClass->products['product_1'];
        
        $model = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_DB]);
        $model->attributes = [
            'id'=>$fixture['id'], 
            'date'=>$fixture['date'], 
            'code'=>$fixture['code'], 
            'name'=>$fixture['name'], 
            'short_description'=>$fixture['short_description'], 
            'description'=>$fixture['description'], 
            'price'=>$fixture['price'], 
            'images'=>$fixture['images'], 
            'id_category'=>$fixture['id_category'], 
            'id_subcategory'=>$fixture['id_subcategory'], 
            'active'=>$fixture['active'], 
            'total_products'=>$fixture['total_products'],
            'seocode'=>$fixture['seocode']
        ];
        
        $this->assertEquals($fixture['id'], $model->id);
        $this->assertEquals($fixture['date'], $model->date);
        $this->assertEquals($fixture['code'], $model->code);
        $this->assertEquals($fixture['name'], $model->name);
        $this->assertEquals($fixture['short_description'], $model->short_description);
        $this->assertEquals($fixture['description'], $model->description);
        $this->assertEquals($fixture['price'], $model->price);
        $this->assertEquals($fixture['images'], $model->images);
        $this->assertEquals($fixture['id_category'], $model->id_category);
        $this->assertEquals($fixture['id_subcategory'], $model->id_subcategory);
        $this->assertEquals($fixture['active'], $model->active);
        $this->assertEquals($fixture['total_products'], $model->total_products);
        $this->assertEquals($fixture['seocode'], $model->seocode);
        
        $model = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM]);
        $model->attributes = [
            'id'=>$fixture['id'], 
            'date'=>$fixture['date'], 
            'code'=>$fixture['code'], 
            'name'=>$fixture['name'], 
            'short_description'=>$fixture['short_description'], 
            'description'=>$fixture['description'], 
            'price'=>$fixture['price'], 
            'images'=>$fixture['images'], 
            'id_category'=>$fixture['id_category'], 
            'id_subcategory'=>$fixture['id_subcategory'], 
            'active'=>$fixture['active'], 
            'total_products'=>$fixture['total_products'],
            'seocode'=>$fixture['seocode']
        ];
        
        $this->assertEquals($fixture['id'], $model->id);
        $this->assertEquals($fixture['date'], $model->date);
        $this->assertEquals($fixture['code'], $model->code);
        $this->assertEquals($fixture['name'], $model->name);
        $this->assertEquals($fixture['short_description'], $model->short_description);
        $this->assertEquals($fixture['description'], $model->description);
        $this->assertEquals($fixture['price'], $model->price);
        $this->assertEquals($fixture['images'], $model->images);
        $this->assertEquals($fixture['id_category'], $model->id_category);
        $this->assertEquals($fixture['id_subcategory'], $model->id_subcategory);
        $this->assertEquals($fixture['active'], $model->active);
        $this->assertEquals($fixture['total_products'], $model->total_products);
        $this->assertEquals($fixture['seocode'], $model->seocode);
    }
    
    /**
     * Тестирует метод ProductsModel::getCategories
     */
    public function testGetCategories()
    {
        $fixture = self::$_dbClass->products['product_2'];
        
        $model = ProductsModel::find()->where(['products.id'=>$fixture['id']])->one();
        
        $this->assertTrue(is_object($model->categories));
        $this->assertTrue($model->categories instanceof CategoriesModel);
    }
    
    /**
     * Тестирует метод ProductsModel::getSubcategory
     */
    public function testGetSubcategory()
    {
        $fixture = self::$_dbClass->products['product_1'];
        
        $model = ProductsModel::find()->where(['products.id'=>$fixture['id']])->one();
        
        $this->assertTrue(is_object($model->subcategory));
        $this->assertTrue($model->subcategory instanceof SubcategoryModel);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
