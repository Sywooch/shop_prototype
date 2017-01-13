<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetCartCheckoutWidgetConfigService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    DeliveriesFixture,
    PaymentsFixture};
use app\models\CurrencyModel;
use app\forms\CustomerInfoForm;

/**
 * Тестирует класс GetCartCheckoutWidgetConfigService
 */
class GetCartCheckoutWidgetConfigServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class,
                'payments'=>PaymentsFixture::class,
                'deliveries'=>DeliveriesFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства GetCartCheckoutWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetCartCheckoutWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('cartCheckoutWidgetArray'));
    }
    
    /**
     * Тестирует метод GetCartCheckoutWidgetConfigService::handle
     */
    public function testHandle()
    {
        $service = new GetCartCheckoutWidgetConfigService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('deliveries', $result);
        $this->assertArrayHasKey('payments', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('view', $result);
        
        $this->assertInternalType('array', $result['deliveries']);
        $this->assertInternalType('array', $result['payments']);
        $this->assertInstanceOf(CurrencyModel::class, $result['currency']);
        $this->assertInstanceOf(CustomerInfoForm::class, $result['form']);
        $this->assertInternalType('string', $result['view']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
