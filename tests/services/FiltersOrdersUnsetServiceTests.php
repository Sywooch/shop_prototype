<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\FiltersOrdersUnsetService;
use app\helpers\HashHelper;

/**
 * Тестирует класс FiltersOrdersUnsetService
 */
class FiltersOrdersUnsetServiceTests extends TestCase
{
    /**
     * Тестирует метод FiltersOrdersUnsetService::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'OrdersFiltersForm'=>[
                        'url'=>'https://shop.com'
                    ]
                ];
            }
        };
        
        $filter = new FiltersOrdersUnsetService();
        $result = $filter->handle($request);
        
        $this->assertEquals('https://shop.com', $result);
        
        $session = \Yii::$app->session;
        $session->open();
        $result = $session->has(HashHelper::createHash([\Yii::$app->params['ordersFilters']]));
        $this->assertFalse($result);
        $session->close();
    }
}
