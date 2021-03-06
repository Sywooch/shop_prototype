<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\BrandsFilterFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CategoriesFixture,
    BrandsFixture,
    ProductsFixture,
    SubcategoryFixture};
use app\models\BrandsModel;

/**
 * Тестирует класс BrandsFilterFinder
 */
class BrandsFilterFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'brands'=>BrandsFixture::class,
                'products'=>ProductsFixture::class,
                'category'=>CategoriesFixture::class,
                'subcategory'=>SubcategoryFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства BrandsFilterFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(BrandsFilterFinder::class);
        
        $this->assertTrue($reflection->hasProperty('category'));
        $this->assertTrue($reflection->hasProperty('subcategory'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод BrandsFilterFinder::setCategory
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetCategoryError()
    {
        $category = null;
        
        $widget = new BrandsFilterFinder();
        $widget->setCategory($category);
    }
    
    /**
     * Тестирует метод BrandsFilterFinder::setCategory
     */
    public function testSetCategory()
    {
        $category = 'category';
        
        $widget = new BrandsFilterFinder();
        $widget->setCategory($category);
        
        $reflection = new \ReflectionProperty($widget, 'category');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод BrandsFilterFinder::setSubcategory
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetSubcategoryError()
    {
        $subcategory = null;
        
        $widget = new BrandsFilterFinder();
        $widget->setSubcategory($subcategory);
    }
    
    /**
     * Тестирует метод BrandsFilterFinder::setSubcategory
     */
    public function testSetSubcategory()
    {
        $subcategory = 'subcategory';
        
        $widget = new BrandsFilterFinder();
        $widget->setSubcategory($subcategory);
        
        $reflection = new \ReflectionProperty($widget, 'subcategory');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод BrandsFilterFinder::run
     */
    public function testRun()
    {
        $finder = new BrandsFilterFinder();
        $result = $finder->find();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(BrandsModel::class, $item);
        }
    }
    
    /**
     * Тестирует метод BrandsFilterFinder::run
     * если не пуст BrandsFilterFinder::category
     */
    public function testRunСategory()
    {
        $finder = new BrandsFilterFinder();
        
        $reflection = new \ReflectionProperty($finder, 'category');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, self::$dbClass->categories['category_1']['seocode']);
        
        $result = $finder->find();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(BrandsModel::class, $item);
        }
    }
    
    /**
     * Тестирует метод BrandsFilterFinder::run
     * если не пуст BrandsFilterFinder::subcategory
     */
    public function testRunSubcategory()
    {
        $finder = new BrandsFilterFinder();
        
        $reflection = new \ReflectionProperty($finder, 'category');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, self::$dbClass->categories['category_1']['seocode']);
        
        $reflection = new \ReflectionProperty($finder, 'subcategory');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, self::$dbClass->subcategory['subcategory_1']['seocode']);
        
        $result = $finder->find();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(BrandsModel::class, $item);
        }
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
