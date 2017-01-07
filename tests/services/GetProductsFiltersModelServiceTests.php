<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetProductsFiltersModelService;
use app\filters\ProductsFilters;
use app\controllers\ProductsListController;

/**
 * Тестирует класс GetProductsFiltersModelService
 */
class GetProductsFiltersModelServiceTests extends TestCase
{
    /**
     * Тестирует свойства GetProductsFiltersModelService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetProductsFiltersModelService::class);
        
        $this->assertTrue($reflection->hasProperty('filtersModel'));
    }
    
    /**
     * Тестирует метод GetProductsFiltersModelService::handle
     */
    public function testHandle()
    {
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $service = new GetProductsFiltersModelService();
        $result = $service->handle();
        
        $this->assertInstanceOf(ProductsFilters::class, $result);
    }
}
