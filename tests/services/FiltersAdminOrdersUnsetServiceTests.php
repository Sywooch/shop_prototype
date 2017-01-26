<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use yii\helpers\Url;
use app\services\FiltersAdminOrdersUnsetService;
use app\helpers\HashHelper;

/**
 * Тестирует класс FiltersAdminOrdersUnsetService
 */
class FiltersAdminOrdersUnsetServiceTests extends TestCase
{
    /**
     * Тестирует метод FiltersAdminOrdersUnsetService::handle
     */
    public function testHandle()
    {
        $key = HashHelper::createHash([\Yii::$app->params['adminOrdersFilters']]);
        
        $session = \Yii::$app->session;
        $session->open();
        $session->set($key, ['status'=>'shipped']);
        
        $result = $session->get($key);
        
        $this->assertSame(['status'=>'shipped'], $result);
        
        $filter = new FiltersAdminOrdersUnsetService();
        $result = $filter->handle();
        
        $this->assertSame(Url::to(['/admin/orders']), $result);
        
        $result = $session->has($key);
        $this->assertFalse($result);
        
        $session->close();
    }
}
