<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\ProductsIdFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\ProductsFixture;
use app\models\ProductsModel;

/**
 * Тестирует класс ProductsIdFinder
 */
class ProductsIdFinderTests extends TestCase
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
        $this->finder = new ProductsIdFinder();
    }
    
    /**
     * Тестирует свойства ProductsIdFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ProductsIdFinder::class);
        
        $this->assertTrue($reflection->hasProperty('idArray'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод ProductsIdFinder::setIdArray
     */
    public function testSetIdArray()
    {
        $this->finder->setIdArray([1,2,3]);
        
        $reflection = new \ReflectionProperty($this->finder, 'idArray');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->finder);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод ProductsIdFinder::find
     * если пуст ProductsIdFinder::idArray
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: idArray
     */
    public function testFindEmptyIdArray()
    {
        $this->finder->find();
    }
    
    /**
     * Тестирует метод ProductsIdFinder::find
     */
    public function testFind()
    {
        $reflection = new \ReflectionProperty($this->finder, 'idArray');
        $reflection->setAccessible(true);
        $reflection->setValue($this->finder, [1,2]);
        
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
