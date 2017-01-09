<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetPasswordGenerateSuccessWidgetConfigService;

/**
 * Тестирует класс GetPasswordGenerateSuccessWidgetConfigService
 */
class GetPasswordGenerateSuccessWidgetConfigServiceTests extends TestCase
{
    /**
     * Тестирует класс GetPasswordGenerateSuccessWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetPasswordGenerateSuccessWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('passwordGenerateSuccessWidgetArray'));
    }
    
    /**
     * Тестирует метод GetPasswordGenerateSuccessWidgetConfigService::handle
     * если $request пуст
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: tempPassword
     */
    public function testHandleEmptyRequest()
    {
        $request = [];
        
        $service = new GetPasswordGenerateSuccessWidgetConfigService();
        $result = $service->handle($request);
    }
    
    /**
     * Тестирует метод GetPasswordGenerateSuccessWidgetConfigService::handle
     */
    public function testHandle()
    {
        $request = ['tempPassword'=>'HjfuafdJ918'];
        
        $service = new GetPasswordGenerateSuccessWidgetConfigService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('tempPassword', $result);
        $this->assertArrayHasKey('view', $result);
        $this->assertInternalType('string', $result['tempPassword']);
        $this->assertInternalType('string', $result['view']);
    }
}
