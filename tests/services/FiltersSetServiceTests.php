<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\FiltersSetService;
use app\helpers\HashHelper;
use yii\helpers\Url;
use app\controllers\ProductsListController;

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
        
        $request = [
            'FiltersForm'=>[
                'sortingField'=>'price',
                'sortingType'=>SORT_ASC,
                'colors'=>[12, 4],
                'sizes'=>[3, 7],
                'brands'=>[2],
                'url'=>$url,
            ]
        ];
        
        $filter = new FiltersSetService();
        $filter->handle($request);
        
        $key = HashHelper::createFiltersKey($url);
        $session = \Yii::$app->session;
        $session->open();
        $result = $session->get($key);
        
        print_r($result);
        
        $session->remove($key);
        $session->close();
    }
}
