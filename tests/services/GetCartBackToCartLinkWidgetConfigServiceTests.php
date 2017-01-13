<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetCartBackToCartLinkWidgetConfigService;

/**
 * Тестирует класс GetCartBackToCartLinkWidgetConfigService
 */
class GetCartBackToCartLinkWidgetConfigServiceTests extends TestCase
{
    /**
     * Тестирует класс GetCartBackToCartLinkWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetCartBackToCartLinkWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('cartBackToCartLinkWidgetArray'));
    }
    
    /**
     * Тестирует метод GetCartBackToCartLinkWidgetConfigService::handle
     */
    public function testHandle()
    {
        $service = new GetCartBackToCartLinkWidgetConfigService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('view', $result);
        $this->assertInternalType('string', $result['view']);
    }
}
