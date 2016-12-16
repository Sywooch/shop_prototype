<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\SizesFilterFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CategoriesFixture,
    SizesFixture,
    ProductsSizesFixture,
    ProductsFixture,
    SubcategoryFixture};
use app\models\SizesModel;

/**
 * Тестирует класс SizesFilterFinder
 */
class SizesFilterFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'sizes'=>SizesFixture::class,
                'products'=>ProductsFixture::class,
                'products_sizes'=>ProductsSizesFixture::class,
                'category'=>CategoriesFixture::class,
                'subcategory'=>SubcategoryFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства SizesFilterFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(SizesFilterFinder::class);
        
        $this->assertTrue($reflection->hasProperty('category'));
        $this->assertTrue($reflection->hasProperty('subcategory'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод SizesFilterFinder::run
     */
    public function testRun()
    {
        $finder = new SizesFilterFinder();
        $result = $finder->find();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(SizesModel::class, $item);
        }
    }
    
    /**
     * Тестирует метод SizesFilterFinder::run
     * если не пуст SizesFilterFinder::category
     */
    public function testRunСategory()
    {
        $finder = new SizesFilterFinder();
        
        $reflection = new \ReflectionProperty($finder, 'category');
        $reflection->setValue($finder, self::$dbClass->categories['category_1']['seocode']);
        
        $result = $finder->find();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(SizesModel::class, $item);
        }
    }
    
    /**
     * Тестирует метод SizesFilterFinder::run
     * если не пуст SizesFilterFinder::subcategory
     */
    public function testRunSubcategory()
    {
        $finder = new SizesFilterFinder();
        
        $reflection = new \ReflectionProperty($finder, 'category');
        $reflection->setValue($finder, self::$dbClass->categories['category_1']['seocode']);
        
        $reflection = new \ReflectionProperty($finder, 'subcategory');
        $reflection->setValue($finder, self::$dbClass->subcategory['subcategory_1']['seocode']);
        
        $result = $finder->find();
        
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
