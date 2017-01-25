<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetAdminOrdersFiltersModelService;
use app\filters\AdminOrdersFilters;

/**
 * Тестирует класс GetAdminOrdersFiltersModelService
 */
class GetAdminOrdersFiltersModelServiceTests extends TestCase
{
    /**
     * Тестирует свойства GetAdminOrdersFiltersModelService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetAdminOrdersFiltersModelService::class);
        
        $this->assertTrue($reflection->hasProperty('filtersModel'));
    }
    
    /**
     * Тестирует метод GetAdminOrdersFiltersModelService::handle
     */
    public function testHandle()
    {
        $service = new GetAdminOrdersFiltersModelService();
        $result = $service->handle();
        
        $this->assertInstanceOf(AdminOrdersFilters::class, $result);
    }
}
