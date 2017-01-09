<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetUserRecoverySuccessWidgetConfigService;

/**
 * Тестирует класс GetUserRecoverySuccessWidgetConfigService
 */
class GetUserRecoverySuccessWidgetConfigServiceTests extends TestCase
{
    /**
     * Тестирует класс GetUserRecoverySuccessWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetUserRecoverySuccessWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('userRecoverySuccessWidgetArray'));
    }
    
    /**
     * Тестирует метод GetUserRecoverySuccessWidgetConfigService::handle
     * если не передан $request
     * @expectedException ErrorException
     */
    public function testHandleRequestError()
    {
        $service = new GetUserRecoverySuccessWidgetConfigService();
        $result = $service->handle();
    }
    
    /**
     * Тестирует метод GetUserRecoverySuccessWidgetConfigService::handle
     * если пуст $request
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: email
     */
    public function testHandleEmptyRequest()
    {
        $request = [];
        
        $service = new GetUserRecoverySuccessWidgetConfigService();
        $result = $service->handle($request);
    }
    
    /**
     * Тестирует метод GetUserRecoverySuccessWidgetConfigService::handle
     */
    public function testHandle()
    {
        $request = ['email'=>'email@email.com'];
        
        $service = new GetUserRecoverySuccessWidgetConfigService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('email', $result);
        $this->assertArrayHasKey('view', $result);
        $this->assertInternalType('string', $result['email']);
        $this->assertInternalType('string', $result['view']);
    }
}
