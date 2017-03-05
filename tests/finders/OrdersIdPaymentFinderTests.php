<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\OrdersIdPaymentFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\PurchasesFixture;
use app\models\PurchasesModel;

/**
 * Тестирует класс OrdersIdPaymentFinder
 */
class OrdersIdPaymentFinderTests extends TestCase
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
        $this->finder = new OrdersIdPaymentFinder();
    }
    
    /**
     * Тестирует свойства OrdersIdPaymentFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(OrdersIdPaymentFinder::class);
        
        $this->assertTrue($reflection->hasProperty('id_payment'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод OrdersIdPaymentFinder::setId_payment
     */
    public function testSetId_payment()
    {
        $this->finder->setId_payment(23);
        
        $reflection = new \ReflectionProperty($this->finder, 'id_payment');
        $reflection->setAccessible(true);
        $result = $reflection->getValue($this->finder);
        
        $this->assertInternalType('integer', $result);
    }
    
    /**
     * Тестирует метод OrdersIdPaymentFinder::find
     * если пуст OrdersIdPaymentFinder::id_payment
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: id_payment
     */
    public function testFindEmptyIdDelivery()
    {
        $this->finder->find();
    }
    
    /**
     * Тестирует метод OrdersIdPaymentFinder::find
     */
    public function testFind()
    {
        $reflection = new \ReflectionProperty($this->finder, 'id_payment');
        $reflection->setAccessible(true);
        $reflection->setValue($this->finder, self::$dbClass->purchases['purchase_1']['id_payment']);
        
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
