<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\ProductsIdColorFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\{ProductsColorsFixture,
    ProductsFixture};
use app\models\ProductsModel;

/**
 * Тестирует класс ProductsIdColorFinder
 */
class ProductsIdColorFinderTests extends TestCase
{
    private static $dbClass;
    private $finder;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
                'products_colors'=>ProductsColorsFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->finder = new ProductsIdColorFinder();
    }
    
    /**
     * Тестирует свойства ProductsIdColorFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ProductsIdColorFinder::class);
        
        $this->assertTrue($reflection->hasProperty('id_color'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод ProductsIdColorFinder::setId_color
     */
    public function testSetId_color()
    {
        $this->finder->setId_color(15);
        
        $reflection = new \ReflectionProperty($this->finder, 'id_color');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->finder);
        
        $this->assertInternalType('integer', $result);
    }
    
    /**
     * Тестирует метод ProductsIdColorFinder::find
     * если пуст ProductsIdColorFinder::id_color
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: id_color
     */
    public function testFindEmptyId_color()
    {
        $this->finder->find();
    }
    
    /**
     * Тестирует метод ProductsIdColorFinder::find
     */
    public function testFind()
    {
        $reflection = new \ReflectionProperty($this->finder, 'id_color');
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
