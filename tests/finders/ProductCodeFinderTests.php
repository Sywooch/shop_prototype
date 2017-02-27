<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\ProductCodeFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\ProductsFixture;
use app\models\ProductsModel;

/**
 * Тестирует класс ProductCodeFinder
 */
class ProductCodeFinderTests extends TestCase
{
    private static $dbClass;
    private $finder;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->finder = new ProductCodeFinder();
    }
    
    /**
     * Тестирует свойства ProductCodeFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ProductCodeFinder::class);
        
        $this->assertTrue($reflection->hasProperty('code'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод ProductCodeFinder::setCode
     */
    public function testSetCode()
    {
        $this->finder->setCode('CODE');
        
        $reflection = new \ReflectionProperty($this->finder, 'code');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->finder);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод ProductCodeFinder::find
     * если пуст ProductCodeFinder::code
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: code
     */
    public function testFindEmptyCode()
    {
        $this->finder->find();
    }
    
    /**
     * Тестирует метод ProductCodeFinder::find
     */
    public function testFind()
    {
        $reflection = new \ReflectionProperty($this->finder, 'code');
        $reflection->setAccessible(true);
        $reflection->setValue($this->finder, self::$dbClass->products['product_1']['code']);
        
        $result = $this->finder->find();
        
        $this->assertInstanceOf(ProductsModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
