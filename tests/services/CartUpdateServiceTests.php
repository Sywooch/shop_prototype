<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\CartUpdateService;
use app\helpers\HashHelper;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    ProductsFixture};

/**
 * Тестирует класс CartUpdateService
 */
class CartUpdateServiceTests extends TestCase
{
    private static $dbClass;
    
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
    }
    
    /**
     * Тестирует метод CartUpdateService::handle
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
                        'quantity'=>null,
                        'id_color'=>2,
                        'id_size'=>2,
                        'id_product'=>1,
                    ]
                ];
            }
        };
        
        $service = new CartUpdateService();
        
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('purchaseform-quantity', $result);
    }
    
    /**
     * Тестирует метод CartUpdateService::handle
     */
    public function testHandle()
    {
        $key = HashHelper::createCartKey();
        
        $session = \Yii::$app->session;
        $session->open();
        $session->set($key, [['quantity'=>2, 'id_color'=>2, 'id_size'=>2, 'id_product'=>1, 'price'=>268.78]]);
        
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'PurchaseForm'=>[
                        'quantity'=>1,
                        'id_color'=>3,
                        'id_size'=>5,
                        'id_product'=>1,
                    ]
                ];
            }
        };
        
        $service = new CartUpdateService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('items', $result);
        $this->assertArrayHasKey('shortCart', $result);
        $this->assertInternalType('string', $result['items']);
        $this->assertInternalType('string', $result['shortCart']);
        
        $result = $session->get($key);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertEquals(1, $result[0]['quantity']);
        $this->assertEquals(3, $result[0]['id_color']);
        $this->assertEquals(5, $result[0]['id_size']);
        $this->assertEquals(1, $result[0]['id_product']);
        $this->assertEquals(268.78, $result[0]['price']);
        
        $session->remove($key);
        $session->close();
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
