<?php

namespace app\tests\models;

use PHPUnit\Framework\TestCase;
use app\tests\DbManager;
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
                'products'=>'app\tests\sources\fixtures\ProductsFixture',
                'subcategory'=>'app\tests\sources\fixtures\SubcategoryFixture',
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
        $model = new SubcategoryModel();
        
        $this->assertTrue(array_key_exists('id', $model->attributes));
        $this->assertTrue(array_key_exists('name', $model->attributes));
        $this->assertTrue(array_key_exists('seocode', $model->attributes));
        $this->assertTrue(array_key_exists('id_category', $model->attributes));
        $this->assertTrue(array_key_exists('active', $model->attributes));
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
    
    /**
     * Тестирует запрос на получение массива объектов
     */
    public function testGetAll()
    {
        $subcategoryQuery = SubcategoryModel::find();
        $subcategoryQuery->extendSelect(['id', 'name', 'seocode', 'id_category', 'active']);
        
        $queryRaw = clone $subcategoryQuery;
        
        $expectedQuery = "SELECT `subcategory`.`id`, `subcategory`.`name`, `subcategory`.`seocode`, `subcategory`.`id_category`, `subcategory`.`active` FROM `subcategory`";
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $subcategoryQuery->all();
        
        $this->assertTrue(is_array($result));
        $this->assertTrue($result[0] instanceof SubcategoryModel);
    }
    
    /**
     * Тестирует запрос на получение 1 объекта
     */
    public function testGetOne()
    {
        $fixture = self::$_dbClass->subcategory['subcategory_1'];
        
        $subcategoryQuery = SubcategoryModel::find();
        $subcategoryQuery->extendSelect(['id', 'name', 'seocode', 'id_category', 'active']);
        $subcategoryQuery->where(['subcategory.seocode'=>$fixture['seocode']]);
        
        $queryRaw = clone $subcategoryQuery;
        
        $expectedQuery = sprintf("SELECT `subcategory`.`id`, `subcategory`.`name`, `subcategory`.`seocode`, `subcategory`.`id_category`, `subcategory`.`active` FROM `subcategory` WHERE `subcategory`.`seocode`='%s'", $fixture['seocode']);
        
        $this->assertEquals($expectedQuery, $queryRaw->createCommand()->getRawSql());
        
        $result = $subcategoryQuery->one();
        
        $this->assertTrue($result instanceof SubcategoryModel);
    }
    
    /**
     * Тестирует метод ExtendActiveQuery::allMap
     */
    public function testAllMap()
    {
        $fixture = self::$_dbClass->subcategory['subcategory_1'];
        $fixture2 = self::$_dbClass->subcategory['subcategory_2'];
        
        $subcategoryQuery = SubcategoryModel::find();
        $subcategoryQuery->extendSelect(['id', 'name']);
        $subcategoryArray = $subcategoryQuery->allMap('id', 'name');
        
        $this->assertFalse(empty($subcategoryArray));
        $this->assertTrue(array_key_exists($fixture['id'], $subcategoryArray));
        $this->assertTrue(array_key_exists($fixture2['id'], $subcategoryArray));
        $this->assertTrue(in_array($fixture['name'], $subcategoryArray));
        $this->assertTrue(in_array($fixture2['name'], $subcategoryArray));
    }
    
    public static function tearDownAfterClass()
    {
        self::$_dbClass->unloadFixtures();
    }
}
