<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\ProductsIdSizeFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\{ProductsSizesFixture,
    ProductsFixture};
use app\models\ProductsModel;

/**
 * Тестирует класс ProductsIdSizeFinder
 */
class ProductsIdSizeFinderTests extends TestCase
{
    private static $dbClass;
    private $finder;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
                'products_sizes'=>ProductsSizesFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->finder = new ProductsIdSizeFinder();
    }
    
    /**
     * Тестирует свойства ProductsIdSizeFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ProductsIdSizeFinder::class);
        
        $this->assertTrue($reflection->hasProperty('id_size'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод ProductsIdSizeFinder::setId_size
     */
    public function testSetId_size()
    {
        $this->finder->setId_size(15);
        
        $reflection = new \ReflectionProperty($this->finder, 'id_size');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->finder);
        
        $this->assertInternalType('integer', $result);
    }
    
    /**
     * Тестирует метод ProductsIdSizeFinder::find
     * если пуст ProductsIdSizeFinder::id_size
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: id_size
     */
    public function testFindEmptyId_size()
    {
        $this->finder->find();
    }
    
    /**
     * Тестирует метод ProductsIdSizeFinder::find
     */
    public function testFind()
    {
        $reflection = new \ReflectionProperty($this->finder, 'id_size');
        $reflection->setAccessible(true);
        $reflection->setValue($this->finder, 1);
        
        $result = $this->finder->find();
        
        $this->assertInternalType('array', $result);
        foreach ($result as $item) {
            $this->assertInstanceOf(ProductsModel::class, $item);
        }
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
