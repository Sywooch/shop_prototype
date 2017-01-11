<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\CartCleanRedirectService;
use app\helpers\HashHelper;

/**
 * Тестирует класс CartCleanRedirectService
 */
class CartCleanRedirectServiceTests extends TestCase
{
    public function setUp()
    {
        \Yii::$app->registry->clean();
    }
    
    /**
     * Тестирует метод CartCleanRedirectService::handle
     * если не переад $request
     * @expectedException ErrorException
     */
    public function testHandleRequestError()
    {
        $service = new CartCleanRedirectService();
        $service->handle();
    }
    
    /**
     * Тестирует метод CartCleanRedirectService::handle
     */
    public function testHandle()
    {
        $session = \Yii::$app->session;
        $session->open();
        $session->set(HashHelper::createCartKey(), [['id_product'=>1, 'quantity'=>1, 'id_color'=>1, 'id_size'=>1, 'price'=>123.87]]);
        
        $this->assertTrue($session->has(HashHelper::createCartKey()));
        
        $request = new class() {
            public $isPost = true;
        };
        
        $service = new CartCleanRedirectService();
        $result = $service->handle($request);
        
        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
        $this->assertEquals('../vendor/phpunit/phpunit/catalog', $result);
        
        $session->remove(HashHelper::createCartKey());
        $session->open();
    }
}
