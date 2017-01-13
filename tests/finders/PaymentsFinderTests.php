<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\PaymentsFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\PaymentsFixture;
use app\models\PaymentsModel;

/**
 * Тестирует класс PaymentsFinder
 */
class PaymentsFinderTests extends TestCase
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
     * Тестирует свойства PaymentsFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(PaymentsFinder::class);
        
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод PaymentsFinder::find
     */
    public function testFind()
    {
        $finder = new PaymentsFinder();
        $payments = $finder->find();
        
        $this->assertInternalType('array', $payments);
        $this->assertNotEmpty($payments);
        foreach($payments as $payment) {
            $this->assertInstanceOf(PaymentsModel::class, $payment);
        }
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
