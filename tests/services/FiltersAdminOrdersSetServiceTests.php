<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use yii\helpers\Url;
use app\services\FiltersAdminOrdersSetService;
use app\helpers\HashHelper;

/**
 * Тестирует класс FiltersAdminOrdersSetService
 */
class FiltersAdminOrdersSetServiceTests extends TestCase
{
    /**
     * Тестирует метод FiltersAdminOrdersSetService::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'AdminOrdersFiltersForm'=>[
                        'sortingType'=>SORT_ASC,
                        'status'=>'shipped',
                    ]
                ];
            }
        };
        
        $filter = new FiltersAdminOrdersSetService();
        $result = $filter->handle($request);
        
        $this->assertEquals(Url::to(['/admin/orders']), $result);
        
        $key = HashHelper::createHash([\Yii::$app->params['adminOrdersFilters']]);
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
