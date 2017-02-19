<?php

namespace app\tests\model;

use PHPUnit\Framework\TestCase;
use app\models\ProductsModel;
use app\tests\DbManager;
use app\tests\sources\fixtures\{BrandsFixture,
    CategoriesFixture,
    ColorsFixture,
    ProductsColorsFixture,
    ProductsFixture,
    ProductsSizesFixture,
    SizesFixture,
    SubcategoryFixture};
use app\models\{BrandsModel,
    CategoriesModel,
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
                'brands'=>BrandsFixture::class,
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
        $this->assertTrue($reflection->hasConstant('SAVE'));
        $this->assertTrue($reflection->hasConstant('EDIT'));
        
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
        
        $model = new ProductsModel(['scenario'=>ProductsModel::SAVE]);
        $model->attributes = [
            'date'=>time(),
            'code'=>'FJHERJ',
            'name'=>'Mock',
            'description'=>'Mock',
            'short_description'=>'Mock',
            'price'=>15.78,
            'images'=>'test',
            'id_category'=>1,
            'id_subcategory'=>1,
            'id_brand'=>1,
            'active'=>true,
            'total_products'=>12,
            'seocode'=>'mock',
        ];
        
        $this->assertEquals(time(), $model->date);
        $this->assertEquals('FJHERJ', $model->code);
        $this->assertEquals('Mock', $model->name);
        $this->assertEquals('Mock', $model->short_description);
        $this->assertEquals(15.78, $model->price);
        $this->assertEquals('test', $model->images);
        $this->assertEquals(1, $model->id_category);
        $this->assertEquals(1, $model->id_subcategory);
        $this->assertEquals(1, $model->id_brand);
        $this->assertEquals(true, $model->active);
        $this->assertEquals(12, $model->total_products);
        $this->assertEquals('mock', $model->seocode);
        
        $model = new ProductsModel(['scenario'=>ProductsModel::EDIT]);
        $model->attributes = [
            'id'=>1,
            'date'=>time(),
            'code'=>'FJHERJ',
            'name'=>'Mock',
            'description'=>'Mock',
            'short_description'=>'Mock',
            'price'=>15.78,
            'images'=>'test',
            'id_category'=>1,
            'id_subcategory'=>1,
            'id_brand'=>1,
            'active'=>true,
            'total_products'=>12,
            'seocode'=>'mock',
            'views'=>12,
        ];
        
        $this->assertEquals(1, $model->id);
        $this->assertEquals(time(), $model->date);
        $this->assertEquals('FJHERJ', $model->code);
        $this->assertEquals('Mock', $model->name);
        $this->assertEquals('Mock', $model->short_description);
        $this->assertEquals(15.78, $model->price);
        $this->assertEquals('test', $model->images);
        $this->assertEquals(1, $model->id_category);
        $this->assertEquals(1, $model->id_subcategory);
        $this->assertEquals(1, $model->id_brand);
        $this->assertEquals(true, $model->active);
        $this->assertEquals(12, $model->total_products);
        $this->assertEquals('mock', $model->seocode);
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
        
        $model = new ProductsModel(['scenario'=>ProductsModel::VIEWS]);
        $model->attributes = [
            'views'=>12
        ];
        $model->validate();
        
        $this->assertEmpty($model->errors);
        
        $model = new ProductsModel(['scenario'=>ProductsModel::SAVE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertCount(11, $model->errors);
        
        $model = new ProductsModel(['scenario'=>ProductsModel::SAVE]);
        $model->attributes = [
            'date'=>time(),
            'code'=>'FJHERJ',
            'name'=>'Mock',
            'description'=>'Mock',
            'short_description'=>'Mock',
            'price'=>15.78,
            'images'=>'test',
            'id_category'=>1,
            'id_subcategory'=>1,
            'id_brand'=>1,
            'seocode'=>self::$dbClass->products['product_1']['seocode'],
        ];
        $model->validate();
        
        $this->assertEmpty($model->errors);
        $this->assertEquals(implode('-', [self::$dbClass->products['product_1']['seocode'], mb_strtolower('FJHERJ', 'UTF-8')]), $model->seocode);
        
        $model = new ProductsModel(['scenario'=>ProductsModel::SAVE]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals(0, $model->active);
        $this->assertEquals(0, $model->total_products);
        $this->assertEquals(0, $model->views);
        
        $model = new ProductsModel(['scenario'=>ProductsModel::EDIT]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertCount(8, $model->errors);
        
        $model = new ProductsModel(['scenario'=>ProductsModel::EDIT]);
        $model->attributes = [
            'id'=>1,
            'code'=>'FJHERJ',
            'name'=>'Mock',
            'price'=>15.78,
            'id_category'=>1,
            'id_subcategory'=>1,
            'id_brand'=>1,
            'seocode'=>'mock',
        ];
        $model->validate();
        
        $this->assertEmpty($model->errors);
        
        $model = new ProductsModel(['scenario'=>ProductsModel::EDIT]);
        $model->attributes = [];
        $model->validate();
        
        $this->assertEquals('', $model->description);
        $this->assertEquals('', $model->short_description);
        $this->assertEquals('', $model->images);
        $this->assertEquals(0, $model->total_products);
        $this->assertEquals(0, $model->views);
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
    
    /**
     * Тестирует метод ProductsModel::getBrand
     */
    public function testGetBrand()
    {
        $model = new ProductsModel();
        $model->id_brand = 1;
        
        $result = $model->brand;
        
        $this->assertInstanceOf(BrandsModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
