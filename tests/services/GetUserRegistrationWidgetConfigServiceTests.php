<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetUserRegistrationWidgetConfigService;
use app\forms\UserRegistrationForm;

/**
 * Тестирует класс GetUserRegistrationWidgetConfigService
 */
class GetUserRegistrationWidgetConfigServiceTests extends TestCase
{
    /**
     * Тестирует свойства GetUserRegistrationWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetUserRegistrationWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('userRegistrationWidgetArray'));
    }
    
    /**
     * Тестирует метод  GetUserRegistrationWidgetConfigService::handle
     */
    public function testHandle()
    {
        $service = new GetUserRegistrationWidgetConfigService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(UserRegistrationForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
}
