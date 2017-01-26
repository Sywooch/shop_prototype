<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetAdminOrdersFiltersWidgetConfigService;
use app\forms\AdminOrdersFiltersForm;

/**
 * Тестирует класс GetAdminOrdersFiltersWidgetConfigService
 */
class GetAdminOrdersFiltersWidgetConfigServiceTests extends TestCase
{
    /**
     * Тестирует свойства GetAdminOrdersFiltersWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetAdminOrdersFiltersWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('adminOrdersFiltersWidgetArray'));
    }
    
    /**
     * Тестирует метод GetAdminOrdersFiltersWidgetConfigService::handle
     */
    public function testHandle()
    {
        $service = new GetAdminOrdersFiltersWidgetConfigService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('sortingTypes', $result);
        $this->assertArrayHasKey('statuses', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('view', $result);
        
        $this->assertInternalType('array', $result['sortingTypes']);
        $this->assertInternalType('array', $result['statuses']);
        $this->assertInstanceOf(AdminOrdersFiltersForm::class, $result['form']);
        $this->assertInternalType('string', $result['view']);
    }
}
