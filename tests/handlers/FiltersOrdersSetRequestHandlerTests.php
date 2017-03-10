<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\FiltersOrdersSetRequestHandler;
use app\helpers\HashHelper;

/**
 * Тестирует класс FiltersOrdersSetRequestHandler
 */
class FiltersOrdersSetRequestHandlerTests extends TestCase
{
    private $handler;
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new FiltersOrdersSetRequestHandler();
    }
    
    /**
     * Тестирует метод FiltersOrdersSetRequestHandler::handle
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
                        'dateFrom'=>time(),
                        'dateTo'=>time(),
                        'url'=>'/shop-test-3'
                    ]
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertEquals('/shop-test', $result);
        
        $key = HashHelper::createHash([\Yii::$app->params['ordersFilters']]);
        $session = \Yii::$app->session;
        $session->open();
        $result = $session->get($key);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('sortingType', $result);
        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('dateFrom', $result);
        $this->assertArrayHasKey('dateTo', $result);
        
        $this->assertSame(SORT_ASC, (int) $result['sortingType']);
        $this->assertSame('shipped', $result['status']);
        $this->assertSame(time(), $result['dateFrom']);
        $this->assertSame(time(), $result['dateTo']);
        
        $session->remove($key);
        $session->close();
    }
}
