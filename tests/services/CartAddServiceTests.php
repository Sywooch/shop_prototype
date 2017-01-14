<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\CartAddService;
use yii\web\Request;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    ProductsFixture};
use app\forms\PurchaseForm;
use app\models\ProductsModel;
use app\helpers\HashHelper;

/**
 * Тестирует класс CartAddService
 */
class CartAddServiceTests extends TestCase
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
     * Тестирует метод CartAddService::handle
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
        
        $service = new CartAddService();
        
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('purchaseform-quantity', $result);
    }
    
    /**
     * Тестирует метод CartAddService::handle
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
        
        $service = new CartAddService();
        
        $result = $service->handle($request);
        
        /*$this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('successInfo', $result);
        $this->assertArrayHasKey('shortCart', $result);
        $this->assertInternalType('string', $result['successInfo']);
        $this->assertInternalType('string', $result['shortCart']);*/
        
        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
        
        $this->assertRegExp('#<p>Товаров в корзине: 2, Общая стоимость: 537,56 UAH</p>#', $result);
        $this->assertRegExp('#<p><a href=".+">В корзину</a></p>#', $result);
        $this->assertRegExp('#<form id="clean-cart-form"#', $result);
        $this->assertRegExp('#<input type="submit" value="Очистить корзину">#', $result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
