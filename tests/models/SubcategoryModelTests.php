<?php

namespace app\tests\models;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\tests\source\fixtures\{ProductsFixture,
    SubcategoryFixture};
use app\models\{CategoriesModel,
    ProductsModel,
    SubcategoryModel};

/**
 * Тестирует класс app\models\SubcategoryModel
 */
class SubcategoryModelTests extends TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::className(),
                'subcategory'=>SubcategoryFixture::className(),
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\SubcategoryModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\SubcategoryModel
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_FORM'));
        
        $this->assertTrue(self::$_reflectionClass->hasProperty('_tableName'));
        
        $model = new SubcategoryModel();
        
        $this->assertTrue(array_key_exists('id', $model->attributes));
        $this->assertTrue(array_key_exists('name', $model->attributes));
        $this->assertTrue(array_key_exists('seocode', $model->attributes));
        $this->assertTrue(array_key_exists('id_category', $model->attributes));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $fixture = self::$_dbClass->subcategory['subcategory_2'];
        
        $model = new SubcategoryModel(['scenario'=>SubcategoryModel::GET_FROM_DB]);
        $model->attributes = [
            'id'=>$fixture['id'], 
            'name'=>$fixture['name'], 
            'seocode'=>$fixture['seocode'], 
            'id_category'=>$fixture['id_category']
        ];
        
        $this->assertEquals($fixture['id'], $model->id);
        $this->assertEquals($fixture['name'], $model->name);
        $this->assertEquals($fixture['seocode'], $model->seocode);
        $this->assertEquals($fixture['id_category'], $model->id_category);
        
        $model = new SubcategoryModel(['scenario'=>SubcategoryModel::GET_FROM_FORM]);
        $model->attributes = [
            'id'=>$fixture['id'], 
            'name'=>$fixture['name'], 
            'seocode'=>$fixture['seocode'], 
            'id_category'=>$fixture['id_category']
        ];
        
        $this->assertEquals($fixture['id'], $model->id);
        $this->assertEquals($fixture['name'], $model->name);
        $this->assertEquals($fixture['seocode'], $model->seocode);
        $this->assertEquals($fixture['id_category'], $model->id_category);
    }
    
    /**
     * Тестирует метод SubcategoryModel::getCategories
     */
    public function testGetCategories()
    {
        $fixture = self::$_dbClass->subcategory['subcategory_1'];
        
        $model = SubcategoryModel::find()->where(['subcategory.id'=>$fixture['id']])->one();
        
        $this->assertTrue(is_object($model->categories));
        $this->assertTrue($model->categories instanceof CategoriesModel);
    }
    
    /**
     * Тестирует метод SubcategoryModel::getProducts
     */
    public function testGetProducts()
    {
        $fixture = self::$_dbClass->subcategory['subcategory_1'];
        
        $model = SubcategoryModel::find()->where(['subcategory.id'=>$fixture['id']])->one();
        
        $this->assertTrue(is_array($model->products));
        $this->assertFalse(empty($model->products));
        $this->assertTrue($model->products[0] instanceof ProductsModel);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
