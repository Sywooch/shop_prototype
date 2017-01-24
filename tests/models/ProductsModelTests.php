<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\models\ProductsModel;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CategoriesFixture,
    ColorsFixture,
    ProductsColorsFixture,
    ProductsFixture,
    ProductsSizesFixture,
    SizesFixture,
    SubcategoryFixture};
use app\models\{CategoriesModel,
    ColorsModel,
    SizesModel,
    SubcategoryModel};

/**
 * Тестирует класс ProductsModel
 */
class ProductsModelTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'categories'=>CategoriesFixture::class,
                'subcategory'=>SubcategoryFixture::class,
                'products'=>ProductsFixture::class,
                'colors'=>ColorsFixture::class,
                'products_colors'=>ProductsColorsFixture::class,
                'sizes'=>SizesFixture::class,
                'products_sizes'=>ProductsSizesFixture::class
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства ProductsModel
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ProductsModel::class);
        
        $this->assertTrue($reflection->hasConstant('VIEWS'));
        
        $model = new ProductsModel();
        
        $this->assertArrayHasKey('id', $model->attributes);
        $this->assertArrayHasKey('date', $model->attributes);
        $this->assertArrayHasKey('code', $model->attributes);
        $this->assertArrayHasKey('name', $model->attributes);
        $this->assertArrayHasKey('description', $model->attributes);
        $this->assertArrayHasKey('short_description', $model->attributes);
        $this->assertArrayHasKey('price', $model->attributes);
        $this->assertArrayHasKey('images', $model->attributes);
        $this->assertArrayHasKey('id_category', $model->attributes);
        $this->assertArrayHasKey('id_subcategory', $model->attributes);
        $this->assertArrayHasKey('id_brand', $model->attributes);
        $this->assertArrayHasKey('active', $model->attributes);
        $this->assertArrayHasKey('total_products', $model->attributes);
        $this->assertArrayHasKey('seocode', $model->attributes);
        $this->assertArrayHasKey('views', $model->attributes);
    }
    
    /**
     * Тестирует метод ProductsModel::scenarios
     */
    public function testScenarios()
    {
        $model = new ProductsModel(['scenario'=>ProductsModel::VIEWS]);
        $model->attributes = [
            'views'=>12
        ];
        
        $this->assertEquals(12, $model->views);
    }
    
    /**
     * Тестирует метод ProductsModel::rules
     */
    public function testRules()
    {
        $model = new ProductsModel(['scenario'=>ProductsModel::VIEWS]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertCount(1, $model->errors);
        $this->assertArrayHasKey('views', $model->errors);
        
        $model = new ProductsModel(['scenario'=>ProductsModel::VIEWS]);
        $model->attributes = [
            'views'=>12
        ];
        $model->validate();
        
        $this->assertEmpty($model->errors);
    }
    
    /**
     * Тестирует метод ProductsModel::tableName
     */
    public function testTableName()
    {
        $result = ProductsModel::tableName();
        
        $this->assertSame('products', $result);
    }
    
    /**
     * Тестирует метод ProductsModel::getCategory
     */
    public function testGetCategory()
    {
        $model = new ProductsModel();
        $model->id_category = 1;
        
        $result = $model->category;
        
        $this->assertInstanceOf(CategoriesModel::class, $result);
    }
    
    /**
     * Тестирует метод ProductsModel::getSubcategory
     */
    public function testGetSubcategory()
    {
        $model = new ProductsModel();
        $model->id_subcategory = 1;
        
        $result = $model->subcategory;
        
        $this->assertInstanceOf(SubcategoryModel::class, $result);
    }
    
    /**
     * Тестирует метод ProductsModel::getColors
     */
    public function testGetColors()
    {
        $model = new ProductsModel();
        $model->id = 1;
        
        $result = $model->colors;
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(ColorsModel::class, $item);
        }
    }
    
    /**
     * Тестирует метод ProductsModel::getSizes
     */
    public function testGetSizes()
    {
        $model = new ProductsModel();
        $model->id = 1;
        
        $result = $model->sizes;
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(SizesModel::class, $item);
        }
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
