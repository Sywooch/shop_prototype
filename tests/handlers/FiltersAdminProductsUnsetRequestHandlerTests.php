<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\FiltersAdminProductsUnsetRequestHandler;
use app\helpers\HashHelper;

/**
 * Тестирует класс FiltersAdminProductsUnsetRequestHandler
 */
class FiltersAdminProductsUnsetRequestHandlerTests extends TestCase
{
    private $handler;
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new FiltersAdminProductsUnsetRequestHandler();
    }
    
    /**
     * Тестирует метод FiltersAdminProductsUnsetRequestHandler::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'AdminProductsFiltersForm'=>[
                        'url'=>'/shop-com'
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
        
        $this->assertEquals('/shop-com', $result);
        
        $session = \Yii::$app->session;
        $session->open();
        $result = $session->has(HashHelper::createHash([\Yii::$app->params['ordersFilters']]));
        $this->assertFalse($result);
        $session->close();
    }
}
