<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\OrdersIdDeliveryFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\PurchasesFixture;
use app\models\PurchasesModel;

/**
 * Тестирует класс OrdersIdDeliveryFinder
 */
class OrdersIdDeliveryFinderTests extends TestCase
{
    private static $dbClass;
    private $finder;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'purchases'=>PurchasesFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->finder = new OrdersIdDeliveryFinder();
    }
    
    /**
     * Тестирует свойства OrdersIdDeliveryFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(OrdersIdDeliveryFinder::class);
        
        $this->assertTrue($reflection->hasProperty('id_delivery'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод OrdersIdDeliveryFinder::setId_delivery
     */
    public function testSetId_delivery()
    {
        $this->finder->setId_delivery(23);
        
        $reflection = new \ReflectionProperty($this->finder, 'id_delivery');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->finder);
        
        $this->assertInternalType('integer', $result);
    }
    
    /**
     * Тестирует метод OrdersIdDeliveryFinder::find
     * если пуст OrdersIdDeliveryFinder::id_delivery
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: id_delivery
     */
    public function testFindEmptyIdDelivery()
    {
        $this->finder->find();
    }
    
    /**
     * Тестирует метод OrdersIdDeliveryFinder::find
     */
    public function testFind()
    {
        $reflection = new \ReflectionProperty($this->finder, 'id_delivery');
        $reflection->setAccessible(true);
        $reflection->setValue($this->finder, self::$dbClass->purchases['purchase_1']['id_delivery']);
        
        $result = $this->finder->find();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        foreach ($result as $item) {
            $this->assertInstanceOf(PurchasesModel::class, $item);
        }
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
