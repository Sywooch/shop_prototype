<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetMailingsUnsubscribeEmptyWidgetConfigPostService;

/**
 * Тестирует класс GetMailingsUnsubscribeEmptyWidgetConfigPostService
 */
class GetMailingsUnsubscribeEmptyWidgetConfigPostServiceTests extends TestCase
{
    /**
     * Тестирует свойства GetMailingsUnsubscribeEmptyWidgetConfigPostService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetMailingsUnsubscribeEmptyWidgetConfigPostService::class);
        
        $this->assertTrue($reflection->hasProperty('mailingsUnsubscribeEmptyWidgetArray'));
    }
    
    /**
     * Тестирует метод GetMailingsUnsubscribeEmptyWidgetConfigPostService::handle
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
        
        $service = new GetMailingsUnsubscribeEmptyWidgetConfigPostService();
        $result = $service->handle($request);
    }
    
    /**
     * Тестирует метод GetMailingsUnsubscribeEmptyWidgetConfigPostService::handle
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
        
        $service = new GetMailingsUnsubscribeEmptyWidgetConfigPostService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('email', $result);
        $this->assertArrayHasKey('view', $result);
        
        $this->assertInternalType('string', $result['email']);
        $this->assertInternalType('string', $result['view']);
    }
}
