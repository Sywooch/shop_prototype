<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\ProductsIdBrandFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\ProductsFixture;
use app\models\ProductsModel;

/**
 * Тестирует класс ProductsIdBrandFinder
 */
class ProductsIdBrandFinderTests extends TestCase
{
    private static $dbClass;
    private $finder;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->finder = new ProductsIdBrandFinder();
    }
    
    /**
     * Тестирует свойства ProductsIdBrandFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ProductsIdBrandFinder::class);
        
        $this->assertTrue($reflection->hasProperty('id_brand'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод ProductsIdBrandFinder::setId_brand
     */
    public function testSetId_brand()
    {
        $this->finder->setId_brand(15);
        
        $reflection = new \ReflectionProperty($this->finder, 'id_brand');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->finder);
        
        $this->assertInternalType('integer', $result);
    }
    
    /**
     * Тестирует метод ProductsIdBrandFinder::find
     * если пуст ProductsIdBrandFinder::id_brand
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: id_brand
     */
    public function testFindEmptyId_brand()
    {
        $this->finder->find();
    }
    
    /**
     * Тестирует метод ProductsIdBrandFinder::find
     */
    public function testFind()
    {
        $reflection = new \ReflectionProperty($this->finder, 'id_brand');
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
