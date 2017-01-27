<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetUserLoginWidgetConfigService;
use app\forms\UserLoginForm;

/**
 * Тестирует класс GetUserLoginWidgetConfigService
 */
class GetUserLoginWidgetConfigServiceTests extends TestCase
{
    /**
     * Тестирует свойства GetUserLoginWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetUserLoginWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('userLoginWidgetArray'));
    }
    
    /**
     * Тестирует метод  GetUserLoginWidgetConfigService::handle
     */
    public function testHandle()
    {
        $service = new GetUserLoginWidgetConfigService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(UserLoginForm::class, $result['form']);
        $this->assertInternalType('string', $result['template']);
    }
}
