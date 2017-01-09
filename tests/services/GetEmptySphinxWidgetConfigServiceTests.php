<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetEmptySphinxWidgetConfigService;

/**
 * Тестирует класс GetEmptySphinxWidgetConfigService
 */
class GetEmptySphinxWidgetConfigServiceTests extends TestCase
{
    /**
     * Тестирует класс GetEmptySphinxWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetEmptySphinxWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('emptySphinxWidgetArray'));
    }
    
    /**
     * Тестирует метод GetEmptySphinxWidgetConfigService::handle
     */
    public function testHandle()
    {
        $service = new GetEmptySphinxWidgetConfigService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('view', $result);
        $this->assertInternalType('string', $result['view']);
    }
}
