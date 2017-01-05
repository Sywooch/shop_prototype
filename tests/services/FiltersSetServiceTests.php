<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\FiltersSetService;
use app\helpers\HashHelper;
use yii\helpers\Url;
use app\controllers\ProductsListController;
use yii\web\Request;

/**
 * Тестирует класс FiltersSetService
 */
class FiltersSetServiceTests extends TestCase
{
    /**
     * Тестирует метод FiltersSetService::handle
     */
    public function testHandle()
    {
        \Yii::$app->controller = new ProductsListController('products-list', \Yii::$app);
        
        $url = Url::current();
        
        $request = new class() extends Request {
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
        
        $filter = new FiltersSetService();
        $result = $filter->handle($request);
        
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
