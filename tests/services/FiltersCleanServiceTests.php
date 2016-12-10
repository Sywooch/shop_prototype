<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\FiltersCleanService;
use app\helpers\{HashHelper,
    StringHelper};

/**
 * Тестирует класс FiltersCleanService
 */
class FiltersCleanServiceTests extends TestCase
{
    /**
     * Тестирует метод FiltersCleanService::handle
     * если данные не валидны
     * @expectedException ErrorException
     */
    public function testHandleError()
    {
        $request = [
            'FiltersForm'=>[]
        ];
        
        $service = new FiltersCleanService();
        $result = $service->handle($request);
    }
    
    /**
     * Тестирует метод FiltersCleanService::handle
     */
    public function testHandle()
    {
        $url = '/shop-12';
        $key = HashHelper::createHash([StringHelper::cutPage($url), \Yii::$app->user->id ?? '']);
        
        $session = \Yii::$app->session;
        $session->open();
        $session->set($key, [1, 23, 'now']);
        $session->close();
        
        $session->open();
        $result = $session->get($key);
        $session->close();
        
        $this->assertSame([1, 23, 'now'], $result);
        
        $request = [
            'FiltersForm'=>['url'=>$url]
        ];
        
        $service = new FiltersCleanService();
        $result = $service->handle($request);
        
        $this->assertSame('/shop', $result);
        
        $session->open();
        $result = $session->has($key);
        $session->close();
        
        $this->assertFalse($result);
    }
}
