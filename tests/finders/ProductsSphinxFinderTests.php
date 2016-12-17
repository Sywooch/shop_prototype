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
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
