<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\FiltersOrdersSetService;
use app\helpers\HashHelper;

/**
 * Тестирует класс FiltersOrdersSetService
 */
class FiltersOrdersSetServiceTests extends TestCase
{
    /**
     * Тестирует метод FiltersOrdersSetService::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'OrdersFiltersForm'=>[
                        'sortingType'=>SORT_ASC,
                        'status'=>'shipped',
                        'url'=>'https://shop.com'
                    ]
                ];
            }
        };
        
        $filter = new FiltersOrdersSetService();
        $result = $filter->handle($request);
        
        $this->assertEquals('https://shop.com', $result);
        
        $key = HashHelper::createHash([\Yii::$app->params['ordersFilters']]);
        $session = \Yii::$app->session;
        $session->open();
        $result = $session->get($key);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('sortingType', $result);
        $this->assertArrayHasKey('status', $result);
        
        $this->assertSame(SORT_ASC, (int) $result['sortingType']);
        $this->assertSame('shipped', $result['status']);
        
        $session->remove($key);
        $session->close();
    }
}
