<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\CartUpdateService;
use yii\web\Request;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    ProductsFixture};
use app\forms\PurchaseForm;
use app\models\ProductsModel;
use app\helpers\HashHelper;

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
        
        $service = new CartUpdateService();
        
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('purchaseform-quantity', $result);
    }
    
    /**
     * Тестирует метод CartUpdateService::handle
     */
    /*public function testHandle()
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
        
        $service = new CartUpdateService();
        
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('successInfo', $result);
        $this->assertArrayHasKey('cartInfo', $result);
        $this->assertInternalType('string', $result['successInfo']);
        $this->assertInternalType('string', $result['cartInfo']);
        
        $this->assertRegExp('#<p>Товар успешно добавлен в корзину!</p>#', $result['successInfo']);
        $this->assertRegExp('#<p>Товаров в корзине: 2, Общая стоимость: 537,56 UAH</p>#', $result['cartInfo']);
        $this->assertRegExp('#<p><a href=".+">В корзину</a></p>#', $result['cartInfo']);
        $this->assertRegExp('#<form id="clean-cart-form"#', $result['cartInfo']);
        $this->assertRegExp('#<input type="submit" value="Очистить">#', $result['cartInfo']);
    }*/
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
