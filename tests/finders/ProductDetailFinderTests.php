<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\ProductDetailFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\ProductsFixture;
use app\models\ProductsModel;

/**
 * Тестирует класс ProductDetailFinder
 */
class ProductDetailFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства ProductDetailFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ProductDetailFinder::class);
        
        $this->assertTrue($reflection->hasProperty('seocode'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод ProductDetailFinder::rules
     */
    public function testRules()
    {
        $finder = new ProductDetailFinder();
        $finder->validate();
        
        $this->assertNotEmpty($finder->errors);
        $this->assertCount(1, $finder->errors);
        $this->assertArrayHasKey('seocode', $finder->errors);
        
        $finder = new ProductDetailFinder();
        $reflection = new \ReflectionProperty($finder, 'seocode');
        $reflection->setValue($finder, 'seocode');
        $finder->validate();
        
        $this->assertEmpty($finder->errors);
    }
    
    /**
     * Тестирует метод ProductDetailFinder::find
     */
    public function testFind()
    {
        $fixture = self::$dbClass->products['product_1'];
        
        $finder = new ProductDetailFinder();
        
        $reflection = new \ReflectionProperty($finder, 'seocode');
        $reflection->setValue($finder, $fixture['seocode']);
        
        $result = $finder->find();
        
        $this->assertInstanceOf(ProductsModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
