<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetSearchBreadcrumbsWidgetConfigService;

/**
 * Тестирует класс GetSearchBreadcrumbsWidgetConfigService
 */
class GetSearchBreadcrumbsWidgetConfigServiceTests extends TestCase
{
    /**
     * Тестирует свойства GetSearchBreadcrumbsWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetSearchBreadcrumbsWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('searchBreadcrumbsWidgetArray'));
    }
    
    /**
     * Тестирует метод GetSearchBreadcrumbsWidgetConfigService::handle
     * если не передан $request
     * @expectedException ErrorException
     */
    public function testHandleRequestError()
    {
        $service = new GetSearchBreadcrumbsWidgetConfigService();
        $service->handle();
    }
    
    /**
     * Тестирует метод GetSearchBreadcrumbsWidgetConfigService::handle
     * если $request пуст
     */
    public function testHandleRequestEmpty()
    {
        $request = new class() {
            public function get($name)
            {
                return null;
            }
        };
        
        $service = new GetSearchBreadcrumbsWidgetConfigService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('text', $result);
        $this->assertInternalType('string', $result['text']);
        $this->assertEmpty($result['text']);
    }
    
    /**
     * Тестирует метод GetSearchBreadcrumbsWidgetConfigService::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public function get($name)
            {
                return 'jacket';
            }
        };
        
        $service = new GetSearchBreadcrumbsWidgetConfigService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('text', $result);
        $this->assertInternalType('string', $result['text']);
        $this->assertEquals('jacket', $result['text']);
    }
}
