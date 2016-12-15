<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\RelatedFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\{ProductsFixture,
    RelatedProductsFixture};
use app\models\ProductsModel;

/**
 * Тестирует класс RelatedFinder
 */
class RelatedFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
                'related_products'=>RelatedProductsFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства RelatedFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(RelatedFinder::class);
        
        $this->assertTrue($reflection->hasProperty('product'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод RelatedFinder::setProduct
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetProductError()
    {
        $product = new class() {};
        
        $finder = new RelatedFinder();
        $finder->setProduct($product);
    }
    
    /**
     * Тестирует метод RelatedFinder::setProduct
     */
    public function testSetProduct()
    {
        $product = new class() extends ProductsModel {};
        
        $finder = new RelatedFinder();
        $finder->setProduct($product);
        
        $reflection = new \ReflectionProperty($finder, 'product');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($finder);
        
        $this->assertInstanceOf(ProductsModel::class, $result);
    }
    
    /**
     * Тестирует метод RelatedFinder::find
     * если пуст RelatedFinder::product
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: product
     */
    public function testFindEmptyProduct()
    {
        $finder = new RelatedFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод RelatedFinder::find
     */
    public function testFind()
    {
        $fixture = self::$dbClass->products['product_1'];
        $product = new class($fixture) extends ProductsModel {};
        
        $finder = new RelatedFinder();
        
        $reflection = new \ReflectionProperty($finder, 'product');
        $reflection->setAccessible(true);
        $reflection->setValue($finder, $product);
        
        $result = $finder->find();
        
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
