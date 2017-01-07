<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetSearchWidgetConfigService;

/**
 * Тестирует класс GetSearchWidgetConfigService
 */
class GetSearchWidgetConfigServiceTests extends TestCase
{
    /**
     * Тестирует свойства GetSearchWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetSearchWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('searchWidgetArray'));
    }
    
    /**
     * Тестирует метод  GetSearchWidgetConfigService::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public function get($name)
            {
                return 'boots';
            }
        };
        
        $service = new GetSearchWidgetConfigService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('text', $result);
        $this->assertArrayHasKey('view', $result);
        $this->assertInternalType('string', $result['text']);
        $this->assertInternalType('string', $result['view']);
    }
}
