<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\FiltersUnsetRequestHandler;
use app\helpers\HashHelper;
use yii\helpers\Url;
use app\controllers\ProductsListController;

/**
 * Тестирует класс FiltersUnsetRequestHandler
 */
class FiltersUnsetRequestHandlerTests extends TestCase
{
    private $handler;
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new FiltersUnsetRequestHandler();
    }
    
    /**
     * Тестирует метод FiltersUnsetRequestHandler::handle
     */
    public function testHandle()
    {
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $url = Url::current();
        
        $key = HashHelper::createFiltersKey($url);
        
        $session = \Yii::$app->session;
        $session->open();
        $session->set($key, ['a'=>1, 'b'=>'cdef', 'c'=>23]);
        
        $result = $session->get($key);
        
        $this->assertSame(['a'=>1, 'b'=>'cdef', 'c'=>23], $result);
        
        $request = new class() {
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'FiltersForm'=>[
                        'url'=>Url::current()
                    ],
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertSame($url, $result);
        
        $result = $session->has($key);
        
        $this->assertFalse($result);
        
        $session->close();
    }
}
