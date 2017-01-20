<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetMailingsUnsubscribeEmptyWidgetConfigService;

/**
 * Тестирует класс GetMailingsUnsubscribeEmptyWidgetConfigService
 */
class GetMailingsUnsubscribeEmptyWidgetConfigServiceTests extends TestCase
{
    /**
     * Тестирует свойства GetMailingsUnsubscribeEmptyWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetMailingsUnsubscribeEmptyWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('unsubscribeEmptyWidgetArray'));
    }
    
    /**
     * Тестирует метод GetMailingsUnsubscribeEmptyWidgetConfigService::handle
     * если пуст $request[email]
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: email
     */
    public function testHandleEmptyEmail()
    {
        $request = new class() {
            public function get($name = null, $defaultValue = null)
            {
                return null;
            }
        };
        
        $service = new GetMailingsUnsubscribeEmptyWidgetConfigService();
        $result = $service->handle($request);
    }
    
    /**
     * Тестирует метод GetMailingsUnsubscribeEmptyWidgetConfigService::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public function get($name = null, $defaultValue = null)
            {
                return 'some@some.com';
            }
        };
        
        $service = new GetMailingsUnsubscribeEmptyWidgetConfigService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('email', $result);
        $this->assertArrayHasKey('view', $result);
        
        $this->assertInternalType('string', $result['email']);
        $this->assertInternalType('string', $result['view']);
    }
}
