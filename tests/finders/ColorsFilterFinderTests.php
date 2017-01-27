<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\ColorsFilterFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CategoriesFixture,
    ColorsFixture,
    ProductsColorsFixture,
    ProductsFixture,
    SubcategoryFixture};
use app\models\ColorsModel;

/**
 * Тестирует класс ColorsFilterFinder
 */
class ColorsFilterFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'colors'=>ColorsFixture::class,
                'products'=>ProductsFixture::class,
                'products_colors'=>ProductsColorsFixture::class,
                'category'=>CategoriesFixture::class,
                'subcategory'=>SubcategoryFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства ColorsFilterFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ColorsFilterFinder::class);
        
        $this->assertTrue($reflection->hasProperty('category'));
        $this->assertTrue($reflection->hasProperty('subcategory'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод ColorsFilterFinder::setCategory
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetCategoryError()
    {
        $category = null;
        
        $widget = new ColorsFilterFinder();
        $widget->setCategory($category);
    }
    
    /**
     * Тестирует метод ColorsFilterFinder::setCategory
     */
    public function testSetCategory()
    {
        $category = 'category';
        
        $widget = new ColorsFilterFinder();
        $widget->setCategory($category);
        
        $reflection = new \ReflectionProperty($widget, 'category');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод ColorsFilterFinder::setSubcategory
     * если передан параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetSubcategoryError()
    {
        $subcategory = null;
        
        $widget = new ColorsFilterFinder();
        $widget->setSubcategory($subcategory);
    }
    
    /**
     * Тестирует метод ColorsFilterFinder::setSubcategory
     */
    public function testSetSubcategory()
    {
        $subcategory = 'subcategory';
        
        $widget = new ColorsFilterFinder();
        $widget->setSubcategory($subcategory);
        
        $reflection = new \ReflectionProperty($widget, 'subcategory');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($widget);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод ColorsFilterFinder::run
     */
    public function testRun()
    {
        $finder = new ColorsFilterFinder();
        $result = $finder->find();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(ColorsModel::class, $item);
        }
    }
    
    /**
     * Тестирует метод ColorsFilterFinder::run
     * если не пуст ColorsFilterFinder::category
     */
    public function testRunСategory()
    {
        $finder = new ColorsFilterFinder();
        
        $reflection = new \ReflectionProperty($finder, 'category');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, self::$dbClass->categories['category_1']['seocode']);
        
        $result = $finder->find();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(ColorsModel::class, $item);
        }
    }
    
    /**
     * Тестирует метод ColorsFilterFinder::run
     * если не пуст ColorsFilterFinder::subcategory
     */
    public function testRunSubcategory()
    {
        $finder = new ColorsFilterFinder();
        
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
            $this->assertInstanceOf(ColorsModel::class, $item);
        }
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
