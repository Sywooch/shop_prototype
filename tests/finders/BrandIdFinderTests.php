<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\BrandIdFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\BrandsFixture;
use app\models\BrandsModel;

/**
 * Тестирует класс BrandIdFinder
 */
class BrandIdFinderTests extends TestCase
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
        $this->finder = new BrandIdFinder();
    }
    
    /**
     * Тестирует свойства BrandIdFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(BrandIdFinder::class);
        
        $this->assertTrue($reflection->hasProperty('id'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод BrandIdFinder::setId
     */
    public function testSetId()
    {
        $this->finder->setId(2);
        
        $reflection = new \ReflectionProperty($this->finder, 'id');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->finder);
        
        $this->assertInternalType('integer', $result);
    }
    
    /**
     * Тестирует метод BrandIdFinder::find
     * если пуст BrandIdFinder::id
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: id
     */
    public function testFindEmptyId()
    {
        $this->finder->find();
    }
    
    /**
     * Тестирует метод BrandIdFinder::find
     */
    public function testFind()
    {
        $reflection = new \ReflectionProperty($this->finder, 'id');
        $reflection->setAccessible(true);
        $reflection->setValue($this->finder, 1);
        
        $result = $this->finder->find();
        
        $this->assertInstanceOf(BrandsModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
