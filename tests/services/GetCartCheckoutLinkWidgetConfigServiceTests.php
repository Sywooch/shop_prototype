<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetCartCheckoutLinkWidgetConfigService;

/**
 * Тестирует класс GetCartCheckoutLinkWidgetConfigService
 */
class GetCartCheckoutLinkWidgetConfigServiceTests extends TestCase
{
    /**
     * Тестирует класс GetCartCheckoutLinkWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetCartCheckoutLinkWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('cartCheckoutLinkWidgetArray'));
    }
    
    /**
     * Тестирует метод GetCartCheckoutLinkWidgetConfigService::handle
     */
    public function testHandle()
    {
        $service = new GetCartCheckoutLinkWidgetConfigService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('view', $result);
        $this->assertInternalType('string', $result['view']);
    }
}
