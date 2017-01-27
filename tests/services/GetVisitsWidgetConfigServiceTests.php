<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetVisitsWidgetConfigService;
use app\tests\DbManager;
use app\tests\sources\fixtures\VisitorsCounterFixture;

/**
 * Тестирует класс GetVisitsWidgetConfigService
 */
class GetVisitsWidgetConfigServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'visitors_counter'=>VisitorsCounterFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства GetVisitsWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetVisitsWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('visitsWidgetArray'));
    }
    
    /**
     * Тестирует метод GetVisitsWidgetConfigService::handle
     */
    public function testHandle()
    {
        $service = new GetVisitsWidgetConfigService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('visitors', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['visitors']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
