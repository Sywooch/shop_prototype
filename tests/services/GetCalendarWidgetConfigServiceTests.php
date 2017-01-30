<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetCalendarWidgetConfigService;

/**
 * Тестирует класс GetCalendarWidgetConfigService
 */
class GetCalendarWidgetConfigServiceTests extends TestCase
{
    /**
     * Тестирует свойства GetCalendarWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetCalendarWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('calendarWidgetArray'));
    }
    
    /**
     * Тестирует метод  GetCalendarWidgetConfigService::handle
     * если request пуст
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: timestamp
     */
    public function testHandleIsGuest()
    {
        $request = new class() {
            public function post($name=null, $defaultValue=null)
            {
                return null;
            }
        };
        
        $service = new GetCalendarWidgetConfigService();
        $service->handle($request);
    }
    
    /**
     * Тестирует метод  GetCalendarWidgetConfigService::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public function post($name=null, $defaultValue=null)
            {
                return time();
            }
        };
        
        $service = new GetCalendarWidgetConfigService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('period', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(\DateTime::class, $result['period']);
        $this->assertInternalType('string', $result['template']);
    }
}
