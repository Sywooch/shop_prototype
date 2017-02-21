<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\ProductsIdSubcategoryFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\ProductsFixture;
use app\models\ProductsModel;

/**
 * Тестирует класс ProductsIdSubcategoryFinder
 */
class ProductsIdSubcategoryFinderTests extends TestCase
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
        $this->finder = new ProductsIdSubcategoryFinder();
    }
    
    /**
     * Тестирует свойства ProductsIdSubcategoryFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(ProductsIdSubcategoryFinder::class);
        
        $this->assertTrue($reflection->hasProperty('id_subcategory'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод ProductsIdSubcategoryFinder::setId_subcategory
     */
    public function testSetId_subcategory()
    {
        $this->finder->setId_subcategory(15);
        
        $reflection = new \ReflectionProperty($this->finder, 'id_subcategory');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->finder);
        
        $this->assertInternalType('integer', $result);
    }
    
    /**
     * Тестирует метод ProductsIdSubcategoryFinder::find
     * если пуст ProductsIdSubcategoryFinder::id_subcategory
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: id_subcategory
     */
    public function testFindEmptyId()
    {
        $this->finder->find();
    }
    
    /**
     * Тестирует метод ProductsIdSubcategoryFinder::find
     */
    public function testFind()
    {
        $reflection = new \ReflectionProperty($this->finder, 'id_subcategory');
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
