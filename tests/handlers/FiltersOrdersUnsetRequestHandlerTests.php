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
                        'url'=>'https://shop.com'
                    ]
                ];
            }
        };
        
        $key = HashHelper::createHash([\Yii::$app->params['adminProductsFilters']]);
        $session = \Yii::$app->session;
        $session->open();
        $session->set($key, ['sortingField'=>'date', 'sortingType'=>SORT_ASC]);
        
        $session->open();
        $result = $session->get($key);
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $result = $this->handler->handle($request);
        
        $this->assertEquals('https://shop.com', $result);
        
        $session = \Yii::$app->session;
        $session->open();
        $result = $session->has(HashHelper::createHash([\Yii::$app->params['ordersFilters']]));
        $this->assertFalse($result);
        $session->close();
    }
}
