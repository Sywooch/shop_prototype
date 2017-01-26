<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetConversionWidgetConfigService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{PurchasesFixture,
    VisitorsCounterFixture};

/**
 * Тестирует класс GetConversionWidgetConfigService
 */
class GetConversionWidgetConfigServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'visitors_counter'=>VisitorsCounterFixture::class,
                'purchases'=>PurchasesFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    /**
     * Тестирует свойства GetConversionWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetConversionWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('conversionWidgetArray'));
    }
    
    /**
     * Тестирует метод GetConversionWidgetConfigService::handle
     */
    public function testHandle()
    {
        $service = new GetConversionWidgetConfigService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('purchases', $result);
        $this->assertArrayHasKey('visits', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('integer', $result['purchases']);
        $this->assertInternalType('integer', $result['visits']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
