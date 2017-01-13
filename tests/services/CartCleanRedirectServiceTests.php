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
        $session->set(HashHelper::createCartCustomerKey(), [
            'name'=>'John',
            'surname'=>'Doe',
            'email'=>'jahn@com.com',
            'phone'=>'+387968965',
            'address'=>'ул. Черноозерная, 1',
            'city'=>'Каркоза',
            'country'=>'Гиады',
            'postcode'=>'08789',
            'delivery'=>1,
            'payment'=>1,
        ]);
        
        $this->assertTrue($session->has(HashHelper::createCartKey()));
        $this->assertTrue($session->has(HashHelper::createCartCustomerKey()));
        
        $request = new class() {
            public $isPost = true;
        };
        
        $service = new CartCleanRedirectService();
        $result = $service->handle($request);
        
        $this->assertFalse($session->has(HashHelper::createCartKey()));
        $this->assertFalse($session->has(HashHelper::createCartCustomerKey()));
        
        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
        $this->assertEquals('../vendor/phpunit/phpunit/catalog', $result);
        
        $session->remove(HashHelper::createCartKey());
        $session->remove(HashHelper::createCartCustomerKey());
        $session->open();
    }
}
