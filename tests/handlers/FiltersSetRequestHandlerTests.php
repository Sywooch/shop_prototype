<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use yii\helpers\Url;
use app\handlers\FiltersSetRequestHandler;
use app\controllers\ProductsListController;
use app\helpers\HashHelper;

/**
 * Тестирует класс FiltersSetRequestHandler
 */
class FiltersSetRequestHandlerTests extends TestCase
{
    private $handler;
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new FiltersSetRequestHandler();
    }
    
    /**
     * Тестирует метод FiltersSetService::handle
     * пуст request
     * @expectedException ErrorException
     */
    public function testHandleError()
    {
        $this->handler->handle();
    }
    
    /**
     * Тестирует метод FiltersSetService::handle
     */
    public function testHandle()
    {
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $url = Url::current();
        
        $request = new class() {
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'FiltersForm'=>[
                        'sortingField'=>'price',
                        'sortingType'=>SORT_ASC,
                        'colors'=>[12, 4],
                        'sizes'=>[3, 7],
                        'brands'=>[2],
                        'url'=>Url::current(),
                    ]
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertSame($url, $result);
        
        $key = HashHelper::createFiltersKey($url);
        $session = \Yii::$app->session;
        $session->open();
        $result = $session->get($key);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('sortingField', $result);
        $this->assertArrayHasKey('sortingType', $result);
        $this->assertArrayHasKey('colors', $result);
        $this->assertArrayHasKey('sizes', $result);
        $this->assertArrayHasKey('brands', $result);
        $this->assertSame('price', $result['sortingField']);
        $this->assertSame(SORT_ASC, (int) $result['sortingType']);
        $this->assertSame([12, 4], $result['colors']);
        $this->assertSame([3, 7], $result['sizes']);
        $this->assertSame([2], $result['brands']);
        
        $session->remove($key);
        $session->close();
    }
}
