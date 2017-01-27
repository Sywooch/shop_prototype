<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetAdminOrdersPaginationWidgetConfigService;
use app\tests\DbManager;
use app\tests\sources\fixtures\PurchasesFixture;
use app\collections\PaginationInterface;

/**
 * Тестирует класс GetAdminOrdersPaginationWidgetConfigService
 */
class GetAdminOrdersPaginationWidgetConfigServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'purchases'=>PurchasesFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства GetAdminOrdersPaginationWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetAdminOrdersPaginationWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('paginationWidgetArray'));
    }
    
    /**
     * Тестирует метод GetAdminOrdersPaginationWidgetConfigService::handle
     * если отсутствует параметр $request
     * @expectedException ErrorException
     */
    public function testHandleEmptyRequest()
    {
        $service = new GetAdminOrdersPaginationWidgetConfigService();
        $service->handle();
    }
    
    /**
     * Тестирует метод GetAdminOrdersPaginationWidgetConfigService::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public function get($name = null, $defaultValue = null)
            {
                return 2;
            }
        };
        
        $service = new GetAdminOrdersPaginationWidgetConfigService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('pagination', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(PaginationInterface::class, $result['pagination']);
        $this->assertInternalType('string', $result['template']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
