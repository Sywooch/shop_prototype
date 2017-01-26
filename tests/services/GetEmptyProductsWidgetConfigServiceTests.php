<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetEmptyProductsWidgetConfigService;

/**
 * Тестирует класс GetEmptyProductsWidgetConfigService
 */
class GetEmptyProductsWidgetConfigServiceTests extends TestCase
{
    /**
     * Тестирует класс GetEmptyProductsWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetEmptyProductsWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('emptyProductsWidgetArray'));
    }
    
    /**
     * Тестирует метод GetEmptyProductsWidgetConfigService::handle
     */
    public function testHandle()
    {
        $service = new GetEmptyProductsWidgetConfigService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('template', $result);
        $this->assertInternalType('string', $result['template']);
    }
}
