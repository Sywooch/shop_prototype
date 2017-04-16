<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\CartAddRequestHandler;
use yii\web\Request;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    ProductsFixture};

/**
 * Тестирует класс CartAddRequestHandler
 */
class CartAddRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'products'=>ProductsFixture::class,
                'currency'=>CurrencyFixture::class
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new CartAddRequestHandler();
    }
    
    /**
     * Тестирует метод CartAddRequestHandler::handle
     * если запрос с ошибками
     */
    public function testHandleErrors()
    {
        $request = new class() extends Request {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'PurchaseForm'=>[
                        'quantity'=>null,
                        'id_color'=>2,
                        'id_size'=>2,
                        'id_product'=>1,
                        'price'=>268.78,
                    ]
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('purchaseform-quantity', $result);
    }
    
    /**
     * Тестирует метод CartAddRequestHandler::handle
     */
    public function testHandle()
    {
        $request = new class() extends Request {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'PurchaseForm'=>[
                        'quantity'=>2,
                        'id_color'=>2,
                        'id_size'=>2,
                        'id_product'=>1,
                        'price'=>268.78,
                    ]
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
        
        $this->assertRegExp('#<a href=".+">Корзина 2<span class="separate">/</span>537,56 &\#8372;</a>#', $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
