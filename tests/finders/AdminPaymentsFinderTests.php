<?php

namespace app\tests\finders;

use PHPUnit\Framework\TestCase;
use app\finders\AdminPaymentsFinder;
use app\tests\DbManager;
use app\tests\sources\fixtures\PaymentsFixture;
use app\models\PaymentsModel;

/**
 * Тестирует класс AdminPaymentsFinder
 */
class AdminPaymentsFinderTests extends TestCase
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
        $this->finder = new AdminPaymentsFinder();
    }
    
    /**
     * Тестирует свойства AdminPaymentsFinder
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminPaymentsFinder::class);
        
        $this->assertTrue($reflection->hasProperty('storage'));
    }
    
    /**
     * Тестирует метод AdminPaymentsFinder::find
     */
    public function testFind()
    {
        $payments = $this->finder->find();
        
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
