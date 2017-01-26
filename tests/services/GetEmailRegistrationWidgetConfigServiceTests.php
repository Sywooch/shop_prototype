<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetEmailRegistrationWidgetConfigService;

/**
 * Тестирует класс GetEmailRegistrationWidgetConfigService
 */
class GetEmailRegistrationWidgetConfigServiceTests extends TestCase
{
    /**
     * Тестирует свойства GetEmailRegistrationWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetEmailRegistrationWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('emailRegistrationWidgetArray'));
    }
    
    /**
     * Тестирует метод GetEmailRegistrationWidgetConfigService::handle
     * если не передан $request
     * @expectedException ErrorException
     */
    public function testHandleErrorRequest()
    {
        $service = new GetEmailRegistrationWidgetConfigService();
        $service->handle();
    }
    
    /**
     * Тестирует метод GetEmailRegistrationWidgetConfigService::handle
     * если не передан $request['email']
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: email
     */
    public function testHandleEmptyEmail()
    {
        $request = [];
        
        $service = new GetEmailRegistrationWidgetConfigService();
        $service->handle($request);
    }
    
    /**
     * Тестирует метод GetEmailRegistrationWidgetConfigService::handle
     */
    public function testHandle()
    {
        $request = ['email'=>'some@some.com'];
        
        $service = new GetEmailRegistrationWidgetConfigService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('email', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('string', $result['email']);
        $this->assertInternalType('string', $result['template']);
    }
}
