<?php

namespace app\tests\models;

use app\tests\DbManager;
use app\models\{CategoriesModel,
    ProductsModel,
    SubcategoryModel};

/**
 * Тестирует класс app\models\ProductsModel
 */
class ProductsModelTests extends \PHPUnit_Framework_TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    private static $_id = 1;
    private static $_date = 1462453595;
    private static $_code = 'YU-6709';
    private static $_name = 'Веселые миниатюры о смерти';
    private static $_description = 'description';
    private static $_price = 14.45;
    private static $_images = 'images/';
    private static $_active = true;
    private static $_total_products = 23;
    private static $_categorySeocode = 'mensfootwear';
    private static $_subcategorySeocode = 'boots';
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager();
        self::$_dbClass->createDb();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\ProductsModel');
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{categories}} SET [[id]]=:id, [[name]]=:name, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':seocode'=>self::$_categorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{subcategory}} SET [[id]]=:id, [[name]]=:name, [[id_category]]=:id_category, [[seocode]]=:seocode');
        $command->bindValues([':id'=>self::$_id, ':name'=>self::$_name, ':id_category'=>self::$_id, ':seocode'=>self::$_subcategorySeocode]);
        $command->execute();
        
        $command = \Yii::$app->db->createCommand('INSERT INTO {{products}} SET [[id]]=:id, [[date]]=:date, [[code]]=:code, [[name]]=:name, [[short_description]]=:short_description, [[description]]=:description, [[price]]=:price, [[images]]=:images, [[id_category]]=:id_category, [[id_subcategory]]=:id_subcategory, [[active]]=:active, [[total_products]]=:total_products');
        $command->bindValues([':id'=>self::$_id, ':date'=>self::$_date, ':code'=>self::$_code, ':name'=>self::$_name, ':short_description'=>self::$_description, ':description'=>self::$_description, ':price'=>self::$_price, ':images'=>self::$_images, ':id_category'=>self::$_id, ':id_subcategory'=>self::$_id, ':active'=>self::$_active, ':total_products'=>self::$_total_products]);
        $command->execute();
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\ProductsModel
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_FORM'));
        
        $this->assertTrue(self::$_reflectionClass->hasProperty('_seocode'));
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
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $model = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_DB]);
        $model->attributes = ['id'=>self::$_id, 'date'=>self::$_date, 'code'=>self::$_code, 'name'=>self::$_name, 'description'=>self::$_description, 'short_description'=>self::$_description, 'price'=>self::$_price, 'images'=>self::$_images, 'id_category'=>self::$_id, 'id_subcategory'=>self::$_id, 'active'=>self::$_active, 'total_products'=>self::$_total_products];
        
        $this->assertEquals(self::$_id, $model->id);
        $this->assertEquals(self::$_date, $model->date);
        $this->assertEquals(self::$_code, $model->code);
        $this->assertEquals(self::$_name, $model->name);
        $this->assertEquals(self::$_description, $model->description);
        $this->assertEquals(self::$_description, $model->short_description);
        $this->assertEquals(self::$_price, $model->price);
        $this->assertEquals(self::$_images, $model->images);
        $this->assertEquals(self::$_id, $model->id_category);
        $this->assertEquals(self::$_id, $model->id_subcategory);
        $this->assertEquals(self::$_active, $model->active);
        $this->assertEquals(self::$_total_products, $model->total_products);
        
        $model = new ProductsModel(['scenario'=>ProductsModel::GET_FROM_FORM]);
        $model->attributes = ['id'=>self::$_id, 'date'=>self::$_date, 'code'=>self::$_code, 'name'=>self::$_name, 'description'=>self::$_description, 'short_description'=>self::$_description, 'price'=>self::$_price, 'images'=>self::$_images, 'id_category'=>self::$_id, 'id_subcategory'=>self::$_id, 'active'=>self::$_active, 'total_products'=>self::$_total_products];
        
        $this->assertEquals(self::$_id, $model->id);
        $this->assertEquals(self::$_date, $model->date);
        $this->assertEquals(self::$_code, $model->code);
        $this->assertEquals(self::$_name, $model->name);
        $this->assertEquals(self::$_description, $model->description);
        $this->assertEquals(self::$_description, $model->short_description);
        $this->assertEquals(self::$_price, $model->price);
        $this->assertEquals(self::$_images, $model->images);
        $this->assertEquals(self::$_id, $model->id_category);
        $this->assertEquals(self::$_id, $model->id_subcategory);
        $this->assertEquals(self::$_active, $model->active);
        $this->assertEquals(self::$_total_products, $model->total_products);
    }
    
    /**
     * Тестирует метод ProductsModel::getCategories
     */
    public function testGetCategories()
    {
        $model = ProductsModel::find()->where(['products.id'=>self::$_id])->one();
        
        $this->assertTrue(is_object($model->categories));
        $this->assertTrue($model->categories instanceof CategoriesModel);
    }
    
    /**
     * Тестирует метод ProductsModel::getSubcategory
     */
    public function testGetSubcategory()
    {
        $model = ProductsModel::find()->where(['products.id'=>self::$_id])->one();
        
        $this->assertTrue(is_object($model->subcategory));
        $this->assertTrue($model->subcategory instanceof SubcategoryModel);
    }
    
    /**
     * Тестирует метод ProductsModel::getSeocode
     */
    public function testGetSeocode()
    {
        $model = new ProductsModel();
        $model->name = self::$_name;
        
        $expect = 'veselye-miniatyury-o-smerti';
        
        $this->assertEquals($expect, $model->seocode);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->deleteDb();
    }
}
