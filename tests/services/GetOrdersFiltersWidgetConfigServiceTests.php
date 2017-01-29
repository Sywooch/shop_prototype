<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetOrdersFiltersWidgetConfigService;
use app\forms\OrdersFiltersForm;
use app\controllers\FiltersController;

/**
 * Тестирует класс GetOrdersFiltersWidgetConfigService
 */
class GetOrdersFiltersWidgetConfigServiceTests extends TestCase
{
    /**
     * Тестирует свойства GetOrdersFiltersWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetOrdersFiltersWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('ordersFiltersWidgetArray'));
    }
    
    /**
     * Тестирует метод GetOrdersFiltersWidgetConfigService::handle
     */
    public function testHandle()
    {
        \Yii::$app->controller = new FiltersController('filters', \Yii::$app);
        
        $service = new GetOrdersFiltersWidgetConfigService();
        $result = $service->handle();
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('sortingTypes', $result);
        //$this->assertArrayHasKey('statuses', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['sortingTypes']);
        //$this->assertInternalType('array', $result['statuses']);
        $this->assertInstanceOf(OrdersFiltersForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
}
