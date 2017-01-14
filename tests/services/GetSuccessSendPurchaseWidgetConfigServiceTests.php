<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetSuccessSendPurchaseWidgetConfigService;

/**
 * Тестирует класс GetSuccessSendPurchaseWidgetConfigService
 */
class GetSuccessSendPurchaseWidgetConfigServiceTests extends TestCase
{
    /**
     * Тестирует класс GetSuccessSendPurchaseWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetSuccessSendPurchaseWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('successSendPurchaseWidgetArray'));
    }
    
    /**
     * Тестирует метод GetSuccessSendPurchaseWidgetConfigService::handle
     */
    public function testHandle()
    {
        $service = new GetSuccessSendPurchaseWidgetConfigService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('view', $result);
        $this->assertInternalType('string', $result['view']);
    }
}
