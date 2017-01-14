<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\PaymentIdFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\PaymentsFixture;
use app\models\PaymentsModel;

/**
 * Тестирует класс PaymentIdFinder
 */
class PaymentIdFinderTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'payments'=>PaymentsFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства PaymentIdFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(PaymentIdFinder::class);
        
        $this->assertTrue($reflection->hasProperty('id'));
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод PaymentIdFinder::find
     * если пуст PaymentIdFinder::id
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: id
     */
    public function testFindEmptyId()
    {
        $finder = new PaymentIdFinder();
        $payments = $finder->find();
    }
    
    /**
     * Тестирует метод PaymentIdFinder::find
     */
    public function testFind()
    {
        $finder = new PaymentIdFinder();
        
        $reflection = new \ReflectionProperty($finder, 'id');
        $reflection->setValue($finder, 1);
        
        $result = $finder->find();
        
        $this->assertInstanceOf(PaymentsModel::class, $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
