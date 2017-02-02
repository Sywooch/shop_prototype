<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\AdminProductsFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\{BrandsFixture,
    CategoriesFixture,
    ProductsColorsFixture,
    ProductsFixture,
    ProductsSizesFixture,
    SubcategoryFixture};
use app\models\ProductsModel;
use app\filters\{AdminProductsFiltersInterface,
    AdminProductsFilters};
use app\collections\ProductsCollection;

/**
 * Тестирует класс AdminProductsFinder
 */
class AdminProductsFinderTests extends TestCase
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
                'products_sizes'=>ProductsSizesFixture::class,
                'brands'=>BrandsFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства AdminProductsFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminProductsFinder::class);
        
        $this->assertTrue($reflection->hasProperty('page'));
        $this->assertTrue($reflection->hasProperty('filters'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод AdminProductsFinder::setFilters
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetFiltersError()
    {
        $filter = new class() {};
        
        $finder = new AdminProductsFinder();
        $finder->setFilters($filter);
    }
    
    /**
     * Тестирует метод AdminProductsFinder::setFilters
     */
    public function testSetFilters()
    {
        $filters = new class() extends AdminProductsFilters {};
        
        $finder = new AdminProductsFinder();
        $finder->setFilters($filters);
        
        $reflection = new \ReflectionProperty($finder, 'filters');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($finder);
        
        $this->assertInstanceOf(AdminProductsFiltersInterface::class, $result);
    }
    
    /**
     * Тестирует метод AdminProductsFinder::setPage
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetPageError()
    {
        $page = null;
        
        $finder = new AdminProductsFinder();
        $finder->setPage($page);
    }
    
    /**
     * Тестирует метод AdminProductsFinder::setPage
     */
    public function testSetPage()
    {
        $page = 2;
        
        $finder = new AdminProductsFinder();
        $finder->setPage($page);
        
        $reflection = new \ReflectionProperty($finder, 'page');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($finder);
        
        $this->assertInternalType('integer', $result);
    }
    
    /**
     * Тестирует метод AdminProductsFinder::find
     * усли пуст AdminProductsFinder::filters
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: filters
     */
    public function testFindEmptyFilters()
    {
        $finder = new AdminProductsFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод AdminProductsFinder::find
     * фильтры пусты
     * страница === 0
     */
    public function testFind()
    {
        $filters = new class() extends AdminProductsFilters {};
        
        $finder = new AdminProductsFinder();
        
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
     * Тестирует метод AdminProductsFinder::find
     * фильтр active === true
     * страница === 0
     */
    public function testFindActive()
    {
        $filters = new class() extends AdminProductsFilters {
            public $active = true;
        };
        
        $finder = new AdminProductsFinder();
        
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
     * Тестирует метод AdminProductsFinder::find
     * фильтр colors === true
     * страница === 0
     */
    public function testFindColors()
    {
        $filters = new class() extends AdminProductsFilters {
            public $colors = [1, 2, 4];
        };
        
        $finder = new AdminProductsFinder();
        
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
     * Тестирует метод AdminProductsFinder::find
     * фильтр sizes === true
     * страница === 0
     */
    public function testFindSizes()
    {
        $filters = new class() extends AdminProductsFilters {
            public $sizes = [1, 2, 4];
        };
        
        $finder = new AdminProductsFinder();
        
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
     * Тестирует метод AdminProductsFinder::find
     * фильтр brands === true
     * страница === 0
     */
    public function testFindBrands()
    {
        $filters = new class() extends AdminProductsFilters {
            public $brands = [1, 2, 4];
        };
        
        $finder = new AdminProductsFinder();
        
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
     * Тестирует метод AdminProductsFinder::find
     * фильтр categories === true
     * страница === 0
     */
    public function testFindCategories()
    {
        $filters = new class() extends AdminProductsFilters {
            public $categories = [1, 2];
        };
        
        $finder = new AdminProductsFinder();
        
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
     * Тестирует метод AdminProductsFinder::find
     * фильтр subcategory === true
     * страница === 0
     */
    public function testFindSubcategory()
    {
        $filters = new class() extends AdminProductsFilters {
            public $subcategory = [1, 2];
        };
        
        $finder = new AdminProductsFinder();
        
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
     * Тестирует метод AdminProductsFinder::find
     * фильтр sortingField === true
     * страница === 0
     */
    public function testFindSortingField()
    {
        $filters = new class() extends AdminProductsFilters {
            public $sortingField = 'views';
        };
        
        $finder = new AdminProductsFinder();
        
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
     * Тестирует метод AdminProductsFinder::find
     * фильтр sortingType === true
     * страница === 0
     */
    public function testFindSortingType()
    {
        $filters = new class() extends AdminProductsFilters {
            public $sortingType = SORT_ASC;
        };
        
        $finder = new AdminProductsFinder();
        
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
     * Тестирует метод AdminProductsFinder::find
     * фильтры пусты
     * страница === true
     */
    public function testFindPage()
    {
        $filters = new class() extends AdminProductsFilters {};
        
        $finder = new AdminProductsFinder();
        
        $reflection = new \ReflectionProperty($finder, 'filters');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $filters);
        
        $reflection = new \ReflectionProperty($finder, 'page');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, 2);
        
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
