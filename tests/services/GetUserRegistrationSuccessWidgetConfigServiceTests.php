<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetUserRegistrationSuccessWidgetConfigService;

/**
 * Тестирует класс GetUserRegistrationSuccessWidgetConfigService
 */
class GetUserRegistrationSuccessWidgetConfigServiceTests extends TestCase
{
    /**
     * Тестирует класс GetUserRegistrationSuccessWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetUserRegistrationSuccessWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('гserRegistrationSuccessWidgetArray'));
    }
    
    /**
     * Тестирует метод GetUserRegistrationSuccessWidgetConfigService::handle
     */
    public function testHandle()
    {
        $service = new GetUserRegistrationSuccessWidgetConfigService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('view', $result);
        $this->assertInternalType('string', $result['view']);
    }
}
