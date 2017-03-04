<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\DeliveryNameFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\DeliveriesFixture;
use app\models\DeliveriesModel;

/**
 * Тестирует класс DeliveryNameFinder
 */
class DeliveryNameFinderTests extends TestCase
{
    private static $dbClass;
    private $finder;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'deliveries'=>DeliveriesFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->finder = new DeliveryNameFinder();
    }
    
    /**
     * Тестирует свойства DeliveryNameFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(DeliveryNameFinder::class);
        
        $this->assertTrue($reflection->hasProperty('name'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод DeliveryNameFinder::setName
     */
    public function testSetName()
    {
        $this->finder->setName('Name');
        
        $reflection = new \ReflectionProperty($this->finder, 'name');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->finder);
        
        $this->assertInternalType('string', $result);
    }
    
    /**
     * Тестирует метод DeliveryNameFinder::find
     * если пуст DeliveryNameFinder::name
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: name
     */
    public function testFindEmptyName()
    {
        $this->finder->find();
    }
    
    /**
     * Тестирует метод DeliveryNameFinder::find
     */
    public function testFind()
    {
        $reflection = new \ReflectionProperty($this->finder, 'name');
        $reflection->setAccessible(true);
        $reflection->setValue($this->finder, self::$dbClass->deliveries['delivery_1']['name']);
        
        $result = $this->finder->find();
        
        $this->assertInstanceOf(DeliveriesModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
