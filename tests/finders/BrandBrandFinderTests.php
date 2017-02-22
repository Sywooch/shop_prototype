<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\BrandBrandFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\BrandsFixture;
use app\models\BrandsModel;

/**
 * Тестирует класс BrandBrandFinder
 */
class BrandBrandFinderTests extends TestCase
{
    private static $dbClass;
    private $finder;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'brands'=>BrandsFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->finder = new BrandBrandFinder();
    }
    
    /**
     * Тестирует свойства BrandBrandFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(BrandBrandFinder::class);
        
        $this->assertTrue($reflection->hasProperty('brand'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод BrandBrandFinder::setBrand
     */
    public function testSetBrand()
    {
        $this->finder->setBrand('brand');
        
        $reflection = new \ReflectionProperty($this->finder, 'brand');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->finder);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод BrandBrandFinder::find
     * если пуст BrandBrandFinder::brand
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: brand
     */
    public function testFindEmptyBrand()
    {
        $this->finder->find();
    }
    
    /**
     * Тестирует метод BrandBrandFinder::find
     */
    public function testFind()
    {
        $reflection = new \ReflectionProperty($this->finder, 'brand');
        $reflection->setAccessible(true);
        $reflection->setValue($this->finder, self::$dbClass->brands['brand_1']['brand']);
        
        $result = $this->finder->find();
        
        $this->assertInstanceOf(BrandsModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
