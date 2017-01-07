<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\ProductsFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CategoriesFixture,
    ProductsColorsFixture,
    ProductsFixture,
    ProductsSizesFixture,
    SubcategoryFixture};
use app\models\ProductsModel;
use app\filters\{ProductsFiltersInterface,
    ProductsFilters};
use app\collections\ProductsCollection;

/**
 * Тестирует класс ProductsFinder
 */
class ProductsFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
                'categories'=>CategoriesFixture::class,
                'subcategory'=>SubcategoryFixture::class,
                'products_colors'=>ProductsColorsFixture::class,
                'products_sizes'=>ProductsSizesFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства ProductsFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ProductsFinder::class);
        
        $this->assertTrue($reflection->hasProperty('category'));
        $this->assertTrue($reflection->hasProperty('subcategory'));
        $this->assertTrue($reflection->hasProperty('page'));
        $this->assertTrue($reflection->hasProperty('filters'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод ProductsFinder::setFilters
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetFiltersError()
    {
        $filter = new class() {};
        
        $finder = new ProductsFinder();
        
        $finder->setFilters($filter);
    }
    
    /**
     * Тестирует метод ProductsFinder::setFilters
     */
    public function testSetFilters()
    {
        $filters = new class() extends ProductsFilters {};
        
        $finder = new ProductsFinder();
        
        $finder->setFilters($filters);
        
        $reflection = new \ReflectionProperty($finder, 'filters');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($finder);
        
        $this->assertInstanceOf(ProductsFiltersInterface::class, $result);
    }
    
    /**
     * Тестирует метод ProductsFinder::find
     * усли пуст ProductsFinder::filters
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: filters
     */
    public function testFindEmptyFilters()
    {
        $finder = new ProductsFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод ProductsFinder::find
     */
    public function testFind()
    {
        $filters = new class() extends ProductsFilters {};
        
        $finder = new ProductsFinder();
        
        $reflection = new \ReflectionProperty($finder, 'filters');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $filters);
        
        $collection = $finder->find();
        
        $this->assertInstanceOf(ProductsCollection::class, $collection);
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
       $this->assertInternalType('array', $result);
       $this->assertNotEmpty($result);
       foreach ($result as $item) {
           $this->assertInstanceOf(ProductsModel::class, $item);
       }
    }
    
    /**
     * Тестирует метод ProductsFinder::find
     * если не пуст ProductsFinder::category
     */
    public function testFindCategory()
    {
        $filters = new class() extends ProductsFilters {};
        
        $finder = new ProductsFinder([
            'category'=>self::$dbClass->categories['category_1']['seocode']
        ]);
        
        $reflection = new \ReflectionProperty($finder, 'filters');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $filters);
        
        $collection = $finder->find();
        
        $this->assertInstanceOf(ProductsCollection::class, $collection);
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
       $this->assertInternalType('array', $result);
       $this->assertNotEmpty($result);
       foreach ($result as $item) {
           $this->assertInstanceOf(ProductsModel::class, $item);
       }
    }
    
    /**
     * Тестирует метод ProductsFinder::find
     * если не пуст ProductsFinder::subcategory
     */
    public function testFindSubcategory()
    {
        $filters = new class() extends ProductsFilters {};
        
        $finder = new ProductsFinder([
            'category'=>self::$dbClass->categories['category_1']['seocode'],
            'subcategory'=>self::$dbClass->subcategory['subcategory_1']['seocode']
        ]);
        
        $reflection = new \ReflectionProperty($finder, 'filters');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $filters);
        
        $collection = $finder->find();
        
        $this->assertInstanceOf(ProductsCollection::class, $collection);
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
       $this->assertInternalType('array', $result);
       $this->assertNotEmpty($result);
       foreach ($result as $item) {
           $this->assertInstanceOf(ProductsModel::class, $item);
       }
    }
    
    /**
     * Тестирует метод ProductsFinder::find
     * если не пуст ProductsFinder::page
     */
    public function testFindPage()
    {
        $filters = new class() extends ProductsFilters {};
        
        $finder = new ProductsFinder([
            'category'=>self::$dbClass->categories['category_1']['seocode'],
            'subcategory'=>self::$dbClass->subcategory['subcategory_1']['seocode'],
            'page'=>2
        ]);
        
        $reflection = new \ReflectionProperty($finder, 'filters');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $filters);
        
        $collection = $finder->find();
        
        $this->assertInstanceOf(ProductsCollection::class, $collection);
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
       $this->assertInternalType('array', $result);
       $this->assertNotEmpty($result);
       foreach ($result as $item) {
           $this->assertInstanceOf(ProductsModel::class, $item);
       }
    }
    
    /**
     * Тестирует метод ProductsFinder::find
     * если не пуст ProductsFinder::filters::colors
     */
    public function testFindFiltersColors()
    {
        $filters = new class() extends ProductsFilters {
            public $colors = [1, 2];
        };
        
        $finder = new ProductsFinder();
        
        $reflection = new \ReflectionProperty($finder, 'filters');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $filters);
        
        $collection = $finder->find();
        
        $this->assertInstanceOf(ProductsCollection::class, $collection);
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
       $this->assertInternalType('array', $result);
       $this->assertNotEmpty($result);
       foreach ($result as $item) {
           $this->assertInstanceOf(ProductsModel::class, $item);
       }
    }
    
    /**
     * Тестирует метод ProductsFinder::find
     * если не пуст ProductsFinder::filters::sizes
     */
    public function testFindFiltersSizes()
    {
        $filters = new class() extends ProductsFilters {
            public $sizes = [1, 2];
        };
        
        $finder = new ProductsFinder();
        
        $reflection = new \ReflectionProperty($finder, 'filters');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $filters);
        
        $collection = $finder->find();
        
        $this->assertInstanceOf(ProductsCollection::class, $collection);
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
       $this->assertInternalType('array', $result);
       $this->assertNotEmpty($result);
       foreach ($result as $item) {
           $this->assertInstanceOf(ProductsModel::class, $item);
       }
    }
    
    /**
     * Тестирует метод ProductsFinder::find
     * если не пуст ProductsFinder::filters::brands
     */
    public function testFindFiltersBrands()
    {
        $filters = new class() extends ProductsFilters {
            public $brands = [1, 2];
        };
        
        $finder = new ProductsFinder();
        
        $reflection = new \ReflectionProperty($finder, 'filters');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $filters);
        
        $collection = $finder->find();
        
        $this->assertInstanceOf(ProductsCollection::class, $collection);
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
       $this->assertInternalType('array', $result);
       $this->assertNotEmpty($result);
       foreach ($result as $item) {
           $this->assertInstanceOf(ProductsModel::class, $item);
       }
    }
    
    /**
     * Тестирует метод ProductsFinder::find
     * если не пуст ProductsFinder::filters::sortingField, ProductsFinder::filters::sortingType
     */
    public function testFindFiltersSorting()
    {
        $filters = new class() extends ProductsFilters {
            public $sortingField = 'price';
            public $sortingType = SORT_ASC;
        };
        
        $finder = new ProductsFinder();
        
        $reflection = new \ReflectionProperty($finder, 'filters');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $filters);
        
        $collection = $finder->find();
        
        $this->assertInstanceOf(ProductsCollection::class, $collection);
        
        $reflection = new \ReflectionProperty($collection, 'items');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($collection);
        
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
