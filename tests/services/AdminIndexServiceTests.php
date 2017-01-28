<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\AdminIndexService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    PurchasesFixture};

/**
 * Тестирует класс AdminIndexService
 */
class AdminIndexServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class,
                'purchases'=>PurchasesFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
    }
    
    /**
     * Тестирует метод AdminIndexService::handle
     */
    public function testHandle()
    {
        $service = new AdminIndexService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('adminTodayOrdersMinimalWidgetConfig', $result);
        $this->assertArrayHasKey('visitsMinimalWidgetConfig', $result);
        $this->assertArrayHasKey('conversionWidgetConfig', $result);
        $this->assertArrayHasKey('averageBillWidgetConfig', $result);
        $this->assertArrayHasKey('popularGoodsWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['adminTodayOrdersMinimalWidgetConfig']);
        $this->assertInternalType('array', $result['visitsMinimalWidgetConfig']);
        $this->assertInternalType('array', $result['conversionWidgetConfig']);
        $this->assertInternalType('array', $result['averageBillWidgetConfig']);
        $this->assertInternalType('array', $result['popularGoodsWidgetConfig']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
