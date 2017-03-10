<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\FiltersOrdersUnsetRequestHandler;
use app\helpers\HashHelper;

/**
 * Тестирует класс FiltersOrdersUnsetRequestHandler
 */
class FiltersOrdersUnsetRequestHandlerTests extends TestCase
{
    private $handler;
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new FiltersOrdersUnsetRequestHandler();
    }
    
    /**
     * Тестирует метод FiltersOrdersUnsetRequestHandler::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'OrdersFiltersForm'=>[
                        'url'=>'/shop-test-3'
                    ]
                ];
            }
        };
        
        $key = HashHelper::createHash([\Yii::$app->params['ordersFilters']]);
        $session = \Yii::$app->session;
        $session->open();
        $session->set($key, ['sortingType'=>SORT_ASC]);
        
        $result = $session->get($key);
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $result = $this->handler->handle($request);
        
        $this->assertEquals('/shop-test', $result);
        
        $session = \Yii::$app->session;
        $session->open();
        $result = $session->has(HashHelper::createHash([\Yii::$app->params['ordersFilters']]));
        $this->assertFalse($result);
        $session->close();
    }
}
