<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\PaymentNameFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\PaymentsFixture;
use app\models\PaymentsModel;

/**
 * Тестирует класс PaymentNameFinder
 */
class PaymentNameFinderTests extends TestCase
{
    private static $dbClass;
    private $finder;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'payments'=>PaymentsFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        $this->finder = new PaymentNameFinder();
    }
    
    /**
     * Тестирует свойства PaymentNameFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(PaymentNameFinder::class);
        
        $this->assertTrue($reflection->hasProperty('name'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод PaymentNameFinder::setName
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
     * Тестирует метод PaymentNameFinder::find
     * если пуст PaymentNameFinder::name
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: name
     */
    public function testFindEmptyName()
    {
        $this->finder->find();
    }
    
    /**
     * Тестирует метод PaymentNameFinder::find
     */
    public function testFind()
    {
        $reflection = new \ReflectionProperty($this->finder, 'name');
        $reflection->setAccessible(true);
        $reflection->setValue($this->finder, self::$dbClass->payments['payment_1']['name']);
        
        $result = $this->finder->find();
        
        $this->assertInstanceOf(PaymentsModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
