<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\models\{CategoriesModel,
    ProductsModel,
    SubcategoryModel};
use app\tests\DbManager;
use app\tests\sources\fixtures\{CategoriesFixture,
    ProductsFixture,
    SubcategoryFixture};

/**
 * Тестирует класс SubcategoryModel
 */
class SubcategoryModelTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'categories'=>CategoriesFixture::class,
                'subcategory'=>SubcategoryFixture::class,
                'products'=>ProductsFixture::class
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства SubcategoryModel
     */
    public function testProperties()
    {
        $model = new SubcategoryModel();
        
        $this->assertArrayHasKey('id', $model->attributes);
        $this->assertArrayHasKey('name', $model->attributes);
        $this->assertArrayHasKey('seocode', $model->attributes);
        $this->assertArrayHasKey('id_category', $model->attributes);
        $this->assertArrayHasKey('active', $model->attributes);
    }
    
    /**
     * Тестирует метод SubcategoryModel::tableName
     */
    public function testTableName()
    {
        $result = SubcategoryModel::tableName();
        
        $this->assertSame('subcategory', $result);
    }
    
    /**
     * Тестирует метод SubcategoryModel::getCategory
     */
    public function testGetCategory()
    {
        $model = new SubcategoryModel();
        $model->id_category = 1;
        
        $result = $model->category;
        
        $this->assertInstanceOf(CategoriesModel::class, $result);
    }
    
    /**
     * Тестирует метод SubcategoryModel::getProducts
     */
    public function testGetProducts()
    {
        $model = new SubcategoryModel();
        $model->id = 1;
        
        $result = $model->products;
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(ProductsModel::class, $item);
        }
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
