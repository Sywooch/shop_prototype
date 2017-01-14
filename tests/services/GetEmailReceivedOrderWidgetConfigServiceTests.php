<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetEmailReceivedOrderWidgetConfigService;
use app\collections\PurchasesCollection;
use app\forms\CustomerInfoForm;
use app\models\CurrencyModel;
use app\tests\DbManager;
use app\tests\sources\fixtures\CurrencyFixture;

/**
 * Тестирует класс GetEmailReceivedOrderWidgetConfigService
 */
class GetEmailReceivedOrderWidgetConfigServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует класс GetEmailReceivedOrderWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetEmailReceivedOrderWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('emailReceivedOrderWidgetArray'));
    }
    
    /**
     * Тестирует метод GetEmailReceivedOrderWidgetConfigService::handle
     */
    public function testHandle()
    {
        $service = new GetEmailReceivedOrderWidgetConfigService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('purchases', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('view', $result);
        
        $this->assertInstanceOf(PurchasesCollection::class, $result['purchases']);
        $this->assertInstanceOf(CustomerInfoForm::class, $result['form']);
        $this->assertInstanceOf(CurrencyModel::class, $result['currency']);
        $this->assertInternalType('string', $result['view']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
