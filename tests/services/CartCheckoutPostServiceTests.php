<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\CartCheckoutPostService;
use app\controllers\CartController;
use app\helpers\HashHelper;
use yii\helpers\Url;

/**
 * Тестирует класс CartCheckoutPostService
 */
class CartCheckoutPostServiceTests extends TestCase
{
    public function setUp()
    {
        \Yii::$app->registry->clean();
    }
    
    /**
     * Тестирует метод CartCheckoutPostService::handle
     * если AJAX
     */
    public function testHandleAjax()
    {
        \Yii::$app->controller = new CartController('cart', \Yii::$app);
        
        $request = new class() {
            public $isAjax = true;
            public function post()
            {
                return [
                    'CustomerInfoForm'=>[
                        'name'=>null,
                        'surname'=>'Doe',
                        'email'=>'jahn@com.com',
                        'phone'=>'+387968965',
                        'address'=>'ул. Черноозерная, 1',
                        'city'=>'Каркоза',
                        'country'=>'Гиады',
                        'postcode'=>'08789',
                        'delivery'=>1,
                        'payment'=>1,
                    ],
                ];
            }
        };
        
        $service = new CartCheckoutPostService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод CartCheckoutPostService::handle
     * если POST
     */
    public function testHandlePost()
    {
        \Yii::$app->controller = new CartController('cart', \Yii::$app);
        
        $key = HashHelper::createCartCustomerKey();
        
        $session = \Yii::$app->session;
        $session->open();
        
        $this->assertFalse($session->has($key));
        
        $request = new class() {
            public $isAjax = false;
            public $isPost = true;
            public function post()
            {
                return [
                    'CustomerInfoForm'=>[
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
                    ],
                ];
            }
        };
        
        $service = new CartCheckoutPostService();
        $result = $service->handle($request);
        
        $this->assertTrue($session->has($key));
        
        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
        $this->assertSame(Url::to(['/cart/confirm']), $result);
        
        $session->remove($key);
        $session->close();
    }
}
