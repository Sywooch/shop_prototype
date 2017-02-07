<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetAdminCsvProductsFormWidgetConfigService;

/**
 * Тестирует класс GetAdminCsvProductsFormWidgetConfigService
 */
class GetAdminCsvProductsFormWidgetConfigServiceTests extends TestCase
{
    /**
     * Тестирует свойства GetAdminCsvProductsFormWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetAdminCsvProductsFormWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('adminCsvProductsFormWidgetArray'));
    }
    
    /**
     * Тестирует метод  GetAdminCsvProductsFormWidgetConfigService::handle
     */
    public function testHandle()
    {
        $service = new GetAdminCsvProductsFormWidgetConfigService();
        $result = $service->get();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
}
