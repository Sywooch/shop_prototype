<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetAdminTodayOrdersMinimalWidgetConfigService;
use app\tests\DbManager;
use app\tests\sources\fixtures\PurchasesFixture;

/**
 * Тестирует класс GetAdminTodayOrdersMinimalWidgetConfigService
 */
class GetAdminTodayOrdersMinimalWidgetConfigServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'orders'=>PurchasesFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства GetAdminTodayOrdersMinimalWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetAdminTodayOrdersMinimalWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('adminTodayOrdersMinimalWidgetArray'));
    }
    
    /**
     * Тестирует метод  GetAdminTodayOrdersMinimalWidgetConfigService::handle
     */
    public function testHandle()
    {
        $service = new GetAdminTodayOrdersMinimalWidgetConfigService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('orders', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('integer', $result['orders']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
