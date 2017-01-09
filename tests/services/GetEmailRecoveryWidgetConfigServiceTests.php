<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetEmailRecoveryWidgetConfigService;

/**
 * Тестирует класс GetEmailRecoveryWidgetConfigService
 */
class GetEmailRecoveryWidgetConfigServiceTests extends TestCase
{
    /**
     * Тестирует свойства GetEmailRecoveryWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetEmailRecoveryWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('emailRecoveryWidgetArray'));
    }
    
    /**
     * Тестирует метод GetEmailRecoveryWidgetConfigService::handle
     * если не передан $request
     * @expectedException ErrorException
     */
    public function testHandleErrorRequest()
    {
        $service = new GetEmailRecoveryWidgetConfigService();
        $service->handle();
    }
    
    /**
     * Тестирует метод GetEmailRecoveryWidgetConfigService::handle
     * если не передан $request['key']
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: key
     */
    public function testHandleEmptyKey()
    {
        $request = ['email'=>'some@some.com'];
        
        $service = new GetEmailRecoveryWidgetConfigService();
        $service->handle($request);
    }
    
    /**
     * Тестирует метод GetEmailRecoveryWidgetConfigService::handle
     * если не передан $request['email']
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: email
     */
    public function testHandleEmptyEmail()
    {
        $request = ['key'=>'Hjhy76rtG'];
        
        $service = new GetEmailRecoveryWidgetConfigService();
        $service->handle($request);
    }
    
    /**
     * Тестирует метод GetEmailRecoveryWidgetConfigService::handle
     */
    public function testHandle()
    {
        $request = ['email'=>'some@some.com', 'key'=>'Hjhy76rtG'];
        
        $service = new GetEmailRecoveryWidgetConfigService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('key', $result);
        $this->assertArrayHasKey('email', $result);
        $this->assertArrayHasKey('view', $result);
        $this->assertInternalType('string', $result['key']);
        $this->assertInternalType('string', $result['email']);
        $this->assertInternalType('string', $result['view']);
    }
}
