<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetProductsFiltersModelServiceAjax;
use app\filters\ProductsFilters;

/**
 * Тестирует класс GetProductsFiltersModelServiceAjax
 */
class GetProductsFiltersModelServiceAjaxTests extends TestCase
{
    /**
     * Тестирует свойства GetProductsFiltersModelServiceAjax
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetProductsFiltersModelServiceAjax::class);
        
        $this->assertTrue($reflection->hasProperty('filtersModel'));
    }
    
    /**
     * Тестирует метод GetProductsFiltersModelServiceAjax::handle
     * если не передан $request
     * @expectedException ErrorException
     */
    public function testHandleErrorRequest()
    {
        $service = new GetProductsFiltersModelServiceAjax();
        $service->handle();
    }
    
    /**
     * Тестирует метод GetProductsFiltersModelServiceAjax::handle
     * если $request пуст
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: key
     */
    public function testHandleEmptyRequest()
    {
        $request = [];
        
        $service = new GetProductsFiltersModelServiceAjax();
        $service->handle($request);
    }
    
    /**
     * Тестирует метод GetProductsFiltersModelServiceAjax::handle
     */
    public function testHandle()
    {
        $request = ['key'=>'key'];
        
        $service = new GetProductsFiltersModelServiceAjax();
        $result = $service->handle($request);
        
        $this->assertInstanceOf(ProductsFilters::class, $result);
    }
}
