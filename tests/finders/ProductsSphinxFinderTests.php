<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\ProductsSphinxFinder;
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
 * Тестирует класс ProductsSphinxFinder
 */
class ProductsSphinxFinderTests extends TestCase
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
     * Тестирует свойства ProductsSphinxFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ProductsSphinxFinder::class);
        
        $this->assertTrue($reflection->hasProperty('sphinx'));
        $this->assertTrue($reflection->hasProperty('page'));
        $this->assertTrue($reflection->hasProperty('filters'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод ProductsSphinxFinder::setSphinx
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetSphinxError()
    {
        $sphinx = new class() {};
        
        $finder = new ProductsSphinxFinder();
        
        $finder->setSphinx($sphinx);
    }
    
    /**
     * Тестирует метод ProductsSphinxFinder::setSphinx
     */
    public function testSetSphinx()
    {
        $sphinx = [1, 12, 34];
        
        $finder = new ProductsSphinxFinder();
        
        $finder->setSphinx($sphinx);
        
        $reflection = new \ReflectionProperty($finder, 'sphinx');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($finder);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод ProductsSphinxFinder::setFilters
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetFiltersError()
    {
        $filters = new class() {};
        
        $finder = new ProductsSphinxFinder();
        
        $finder->setFilters($filters);
    }
    
    /**
     * Тестирует метод ProductsSphinxFinder::setFilters
     */
    public function testSetFilters()
    {
        $filters = new class() extends ProductsFilters {};
        
        $finder = new ProductsSphinxFinder();
        
        $finder->setFilters($filters);
        
        $reflection = new \ReflectionProperty($finder, 'filters');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($finder);
        
        $this->assertInstanceOf(ProductsFiltersInterface::class, $result);
    }
    
    /**
     * Тестирует метод ProductsSphinxFinder::find
     * если пуст ProductsSphinxFinder::sphinx
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: sphinx
     */
    public function testFindEmptySphinx()
    {
        $finder = new ProductsSphinxFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод ProductsSphinxFinder::find
     * если пуст ProductsSphinxFinder::filters
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: filters
     */
    public function testFindEmptyFilters()
    {
        $finder = new ProductsSphinxFinder();
        
        $reflection = new \ReflectionProperty($finder, 'sphinx');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, [1]);
        
        $finder->find();
    }
    
    /**
     * Тестирует метод ProductsSphinxFinder::find
     */
    public function testFind()
    {
        $sphinx = [1, 2, 3, 4, 5];
        $filters = new class() extends ProductsFilters {};
        
        $finder = new ProductsSphinxFinder();
        
        $reflection = new \ReflectionProperty($finder, 'filters');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $filters);
        
        $reflection = new \ReflectionProperty($finder, 'sphinx');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $sphinx);
        
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
     * Тестирует метод ProductsSphinxFinder::find
     * если не пуст ProductsSphinxFinder::page
     */
    public function testFindPage()
    {
        $sphinx = [1, 2, 3, 4, 5];
        $filters = new class() extends ProductsFilters {};
        
        $finder = new ProductsSphinxFinder();
        
        $reflection = new \ReflectionProperty($finder, 'filters');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $filters);
        
        $reflection = new \ReflectionProperty($finder, 'sphinx');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $sphinx);
        
        $reflection = new \ReflectionProperty($finder, 'page');
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
    
    /**
     * Тестирует метод ProductsSphinxFinder::find
     * если не пуст ProductsSphinxFinder::filters::colors
     */
    public function testFindFiltersColors()
    {
        $sphinx = [1, 2, 3, 4, 5];
        $filters = new class() extends ProductsFilters {
            public $colors = [1, 2];
        };
        
        $finder = new ProductsSphinxFinder();
        
        $reflection = new \ReflectionProperty($finder, 'filters');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $filters);
        
        $reflection = new \ReflectionProperty($finder, 'sphinx');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $sphinx);
        
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
     * Тестирует метод ProductsSphinxFinder::find
     * если не пуст ProductsSphinxFinder::filters::sizes
     */
    public function testFindFiltersSizes()
    {
        $sphinx = [1, 2, 3, 4, 5];
        $filters = new class() extends ProductsFilters {
            public $sizes = [1, 2];
        };
        
        $finder = new ProductsSphinxFinder();
        
        $reflection = new \ReflectionProperty($finder, 'filters');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $filters);
        
        $reflection = new \ReflectionProperty($finder, 'sphinx');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $sphinx);
        
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
     * Тестирует метод ProductsSphinxFinder::find
     * если не пуст ProductsSphinxFinder::filters::brands
     */
    public function testFindFiltersBrands()
    {
        $sphinx = [1, 2, 3, 4, 5];
        $filters = new class() extends ProductsFilters {
            public $brands = [1, 2];
        };
        
        $finder = new ProductsSphinxFinder();
        
        $reflection = new \ReflectionProperty($finder, 'filters');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $filters);
        
        $reflection = new \ReflectionProperty($finder, 'sphinx');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $sphinx);
        
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
     * Тестирует метод ProductsSphinxFinder::find
     * если не пуст ProductsSphinxFinder::filters::sortingField, ProductsSphinxFinder::filters::sortingType
     */
    public function testFindFiltersSorting()
    {
        $sphinx = [1, 2, 3, 4, 5];
        $filters = new class() extends ProductsFilters {
            public $sortingField = 'price';
            public $sortingType = SORT_ASC;
        };
        
        $finder = new ProductsSphinxFinder();
        
        $reflection = new \ReflectionProperty($finder, 'filters');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $filters);
        
        $reflection = new \ReflectionProperty($finder, 'sphinx');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $sphinx);
        
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
