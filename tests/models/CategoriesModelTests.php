<?php

namespace app\tests\models;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
use app\tests\source\fixtures\{CategoriesFixture,
    SubcategoryFixture,
    ProductsFixture};
use app\models\{CategoriesModel,
    ProductsModel,
    SubcategoryModel};

/**
 * Тестирует класс app\models\CategoriesModel
 */
class CategoriesModelTests extends TestCase
{
    private static $_dbClass;
    private static $_reflectionClass;
    
    public static function setUpBeforeClass()
    {
        self::$_dbClass = new DbManager([
            'fixtures'=>[
                'categories'=>CategoriesFixture::className(),
                'subcategory'=>SubcategoryFixture::className(),
                'products'=>ProductsFixture::className(),
            ],
        ]);
        self::$_dbClass->loadFixtures();
        
        self::$_reflectionClass = new \ReflectionClass('\app\models\CategoriesModel');
    }
    
    /**
     * Тестирует наличие свойств у объекта app\models\CategoriesModel
     */
    public function testProperties()
    {
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_DB'));
        $this->assertTrue(self::$_reflectionClass->hasConstant('GET_FROM_FORM'));
        
        $this->assertTrue(self::$_reflectionClass->hasProperty('_tableName'));
        
        $model = new CategoriesModel();
        
        $this->assertTrue(array_key_exists('id', $model->attributes));
        $this->assertTrue(array_key_exists('name', $model->attributes));
        $this->assertTrue(array_key_exists('seocode', $model->attributes));
    }
    
    /**
     * Тестирует сценарии
     */
    public function testScenarios()
    {
        $fixture = self::$_dbClass->categories['category_1'];
        
        $model = new CategoriesModel(['scenario'=>CategoriesModel::GET_FROM_DB]);
        $model->attributes = [
            'id'=>$fixture['id'], 
            'name'=>$fixture['name'], 
            'seocode'=>$fixture['seocode']
        ];
        
        $this->assertEquals($fixture['id'], $model->id);
        $this->assertEquals($fixture['name'], $model->name);
        $this->assertEquals($fixture['seocode'], $model->seocode);
        
        $model = new CategoriesModel(['scenario'=>CategoriesModel::GET_FROM_FORM]);
        $model->attributes = [
            'id'=>$fixture['id'], 
            'name'=>$fixture['name'], 
            'seocode'=>$fixture['seocode']
        ];
        
        $this->assertEquals($fixture['id'], $model->id);
        $this->assertEquals($fixture['name'], $model->name);
        $this->assertEquals($fixture['seocode'], $model->seocode);
    }
    
    /**
     * Тестирует метод CategoriesModel::getSubcategory
     */
    public function testGetSubcategory()
    {
        $fixture = self::$_dbClass->categories['category_1'];
        
        $model = CategoriesModel::find()->where(['categories.id'=>$fixture['id']])->one();
        
        $this->assertTrue(is_array($model->subcategory));
        $this->assertFalse(empty($model->subcategory));
        $this->assertTrue(is_object($model->subcategory[0]));
        $this->assertTrue($model->subcategory[0] instanceof SubcategoryModel);
    }
    
    /**
     * Тестирует метод CategoriesModel::getProducts
     */
    public function testGetProducts()
    {
        $fixture = self::$_dbClass->categories['category_1'];
        
        $model = CategoriesModel::find()->where(['categories.id'=>$fixture['id']])->one();
        
        $this->assertTrue(is_array($model->products));
        $this->assertFalse(empty($model->products));
        $this->assertTrue(is_object($model->products[0]));
        $this->assertTrue($model->products[0] instanceof ProductsModel);
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
