<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\CartDeleteRequestHandler;
use app\helpers\HashHelper;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    ProductsFixture};

/**
 * Тестирует класс CartDeleteRequestHandler
 */
class CartDeleteRequestHandlerTests extends TestCase
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
        
        $this->handler = new CartDeleteRequestHandler();
    }
    
    /**
     * Тестирует метод CartDeleteRequestHandler::handle
     * если запрос с ошибками
     */
    public function testHandleErrors()
    {
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'PurchaseForm'=>[
                        'id_product'=>null,
                    ]
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('purchaseform-id_product', $result);
    }
    
    /**
     * Тестирует метод CartDeleteRequestHandler::handle
     * если удаляемый объект не единственный
     */
    public function testHandleNotAlone()
    {
        $key = HashHelper::createCartKey();
        
        $session = \Yii::$app->session;
        $session->open();
        $session->set($key, [
            ['quantity'=>1, 'id_color'=>3, 'id_size'=>1, 'id_product'=>14, 'price'=>965.00],
            ['quantity'=>2, 'id_color'=>2, 'id_size'=>2, 'id_product'=>1, 'price'=>268.78],
        ]);
        
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'PurchaseForm'=>[
                        'id_product'=>14,
                    ]
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('items', $result);
        $this->assertArrayHasKey('shortCart', $result);
        $this->assertInternalType('string', $result['items']);
        $this->assertInternalType('string', $result['shortCart']);
        
        $result = $session->get($key);
        
        $this->assertInternalType('array', $result);
        $this->assertCount(1, $result);
        $this->assertEquals(2, $result[0]['quantity']);
        $this->assertEquals(2, $result[0]['id_color']);
        $this->assertEquals(2, $result[0]['id_size']);
        $this->assertEquals(1, $result[0]['id_product']);
        $this->assertEquals(268.78, $result[0]['price']);
        
        $session->remove($key);
        $session->close();
    }
    
    /**
     * Тестирует метод CartDeleteRequestHandler::handle
     * если удаляемый объект единственный
     */
    public function testHandleAlone()
    {
        $key = HashHelper::createCartKey();
        
        $session = \Yii::$app->session;
        $session->open();
        $session->set($key, [['quantity'=>1, 'id_color'=>3, 'id_size'=>1, 'id_product'=>14, 'price'=>965.00]]);
        
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'PurchaseForm'=>[
                        'id_product'=>14,
                    ]
                ];
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
        $this->assertEquals('../vendor/phpunit/phpunit/catalog', $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
