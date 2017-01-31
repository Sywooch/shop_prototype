<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetAdminCsvOrdersFormWidgetConfigService;

/**
 * Тестирует класс GetAdminCsvOrdersFormWidgetConfigService
 */
class GetAdminCsvOrdersFormWidgetConfigServiceTests extends TestCase
{
    /**
     * Тестирует свойства GetAdminCsvOrdersFormWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetAdminCsvOrdersFormWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('adminCsvOrdersFormWidgetArray'));
    }
    
    /**
     * Тестирует метод  GetAdminCsvOrdersFormWidgetConfigService::handle
     */
    public function testHandle()
    {
        $service = new GetAdminCsvOrdersFormWidgetConfigService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
}
