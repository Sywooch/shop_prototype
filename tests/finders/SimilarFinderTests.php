<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\SimilarFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\{ColorsFixture,
    ProductsColorsFixture,
    ProductsFixture,
    ProductsSizesFixture,
    SizesFixture};
use app\models\ProductsModel;

/**
 * Тестирует класс SimilarFinder
 */
class SimilarFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
                'colors'=>ColorsFixture::class,
                'sizes'=>SizesFixture::class,
                'products_colors'=>ProductsColorsFixture::class,
                'products_sizes'=>ProductsSizesFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства SimilarFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(SimilarFinder::class);
        
        $this->assertTrue($reflection->hasProperty('product'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод SimilarFinder::setProduct
     * передаю параметр неверного типа
     * @expectedException TypeError
     */
    public function testSetProductError()
    {
        $product = new class() {};
        
        $finder = new SimilarFinder();
        $finder->setProduct($product);
    }
    
    /**
     * Тестирует метод SimilarFinder::setProduct
     */
    public function testSetProduct()
    {
        $product = new class() extends ProductsModel {};
        
        $finder = new SimilarFinder();
        $finder->setProduct($product);
        
        $reflection = new \ReflectionProperty($finder, 'product');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($finder);
        
        $this->assertInstanceOf(ProductsModel::class, $result);
    }
    
    /**
     * Тестирует метод SimilarFinder::find
     * если пуст SimilarFinder::product
     * @expectedException ErrorException
     * @expectedExceptionMessage Missing required data: product
     */
    public function testFindEmptyProduct()
    {
        $finder = new SimilarFinder();
        $finder->find();
    }
    
    /**
     * Тестирует метод SimilarFinder::find
     */
    public function testFind()
    {
        $fixture = self::$dbClass->products['product_1'];
        $product = new class($fixture) extends ProductsModel {};
        
        $finder = new SimilarFinder();
        
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
