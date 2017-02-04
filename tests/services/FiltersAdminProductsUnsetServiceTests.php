<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\FiltersAdminProductsUnsetService;
use app\helpers\HashHelper;

/**
 * Тестирует класс FiltersAdminProductsUnsetService
 */
class FiltersAdminProductsUnsetServiceTests extends TestCase
{
    /**
     * Тестирует метод FiltersAdminProductsUnsetService::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'AdminProductsFiltersForm'=>[
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
        
        $filter = new FiltersAdminProductsUnsetService();
        $result = $filter->handle($request);
        
        $this->assertEquals('https://shop.com', $result);
        
        $session = \Yii::$app->session;
        $session->open();
        $result = $session->has(HashHelper::createHash([\Yii::$app->params['ordersFilters']]));
        $this->assertFalse($result);
        $session->close();
    }
}
