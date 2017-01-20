<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetUnsubscribeEmptyWidgetConfigPostService;

/**
 * Тестирует класс GetUnsubscribeEmptyWidgetConfigPostService
 */
class GetUnsubscribeEmptyWidgetConfigPostServiceTests extends TestCase
{
    /**
     * Тестирует свойства GetUnsubscribeEmptyWidgetConfigPostService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetUnsubscribeEmptyWidgetConfigPostService::class);
        
        $this->assertTrue($reflection->hasProperty('mailingsUnsubscribeEmptyWidgetArray'));
    }
    
    /**
     * Тестирует метод GetUnsubscribeEmptyWidgetConfigPostService::handle
     * если пуст $request[email]
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: email
     */
    public function testHandleEmptyEmail()
    {
        $request = new class() {
            public function post($name = null, $defaultValue = null)
            {
                return null;
            }
        };
        
        $service = new GetUnsubscribeEmptyWidgetConfigPostService();
        $result = $service->handle($request);
    }
    
    /**
     * Тестирует метод GetUnsubscribeEmptyWidgetConfigPostService::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'MailingForm'=>[
                        'email'=>'some@some.com'
                    ],
                ];
            }
        };
        
        $service = new GetUnsubscribeEmptyWidgetConfigPostService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('email', $result);
        $this->assertArrayHasKey('view', $result);
        
        $this->assertInternalType('string', $result['email']);
        $this->assertInternalType('string', $result['view']);
    }
}
