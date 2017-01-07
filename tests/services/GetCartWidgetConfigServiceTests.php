<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetEmptyProductsWidgetConfigService;

/**
 * Тестирует класс GetEmptyProductsWidgetConfigService
 */
class GetEmptyProductsWidgetConfigServiceTests extends TestCase
{
    /**
     * Тестирует свойства GetEmptyProductsWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetEmptyProductsWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('emptyProductsWidgetArray'));
    }
    
    /**
     * Тестирует свойства GetEmptyProductsWidgetConfigService::handle
     */
    public function testHandle()
    {
        $service = new GetEmptyProductsWidgetConfigService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertArrayHasKey('view', $result);
        $this->assertInternalType('string', $result['view']);
    }
}
