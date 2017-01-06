<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\PurchaseSaveService;
use yii\web\Request;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    ProductsFixture};
use app\forms\PurchaseForm;
use app\models\ProductsModel;
use app\helpers\HashHelper;

/**
 * Тестирует класс PurchaseSaveService
 */
class PurchaseSaveServiceTests extends TestCase
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
    
    /**
     * Тестирует свойства PurchaseSaveService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(PurchaseSaveService::class);
        
        $this->assertTrue($reflection->hasProperty('toCartWidgetArray'));
        $this->assertTrue($reflection->hasProperty('form'));
    }
    
    /**
     * Тестирует метод PurchaseSaveService::getToCartWidgetArray
     * если не найден товар по запросу
     * @expectedException yii\web\NotFoundHttpException
     */
    public function testGetToCartWidgetArray404()
    {
        $request = new class() extends Request {
            public function get($name = null, $defaultValue = null)
            {
                return 'nothing';
            }
        };
        
        $service = new PurchaseSaveService();
        
        $reflection = new \ReflectionMethod($service, 'getToCartWidgetArray');
        $reflection->setAccessible(true);
        $reflection->invoke($service, $request);
    }
    
    /**
     * Тестирует метод PurchaseSaveService::getToCartWidgetArray
     */
    public function testGetToCartWidgetArray()
    {
        $request = new class() extends Request {
            public $seocode;
            public function get($name = null, $defaultValue = null)
            {
                return $this->seocode;
            }
        };
        $reflection = new \ReflectionProperty($request, 'seocode');
        $result = $reflection->setValue($request, self::$dbClass->products['product_1']['seocode']);
        
        $service = new PurchaseSaveService();
        
        $reflection = new \ReflectionProperty($service, 'form');
        $reflection->setAccessible(true);
        $reflection->setValue($service, new PurchaseForm(['scenario'=>PurchaseForm::SAVE]));
        
        $reflection = new \ReflectionMethod($service, 'getToCartWidgetArray');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service, $request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('product', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('view', $result);
        $this->assertInstanceOf(ProductsModel::class, $result['product']);
        $this->assertInstanceOf(PurchaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['view']);
    }
    
    /**
     * Тестирует метод PurchaseSaveService::getCartInfo
     */
    public function testGetCartInfo()
    {
        $key = HashHelper::createCartKey();
        
        $paurchases = [['id_product'=>1, 'quantity'=>2, 'price'=>1238.09]];
        
        $session = \Yii::$app->session;
        $session->open();
        $session->set($key, $paurchases);
        
        $service = new PurchaseSaveService();
        
        $reflection = new \ReflectionMethod($service, 'getCartInfo');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($service);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('successInfo', $result);
        $this->assertArrayHasKey('cartInfo', $result);
        $this->assertInternalType('string', $result['successInfo']);
        $this->assertInternalType('string', $result['cartInfo']);
        
        $this->assertRegExp('#<p>Товар успешно добавлен в корзину!</p>#', $result['successInfo']);
        $this->assertRegExp('#<p>Товаров в корзине: 2, Общая стоимость: 2476,18 UAH</p>#', $result['cartInfo']);
        $this->assertRegExp('#<p><a href=".+">В корзину</a></p>#', $result['cartInfo']);
        $this->assertRegExp('#<form id="clean-cart-form"#', $result['cartInfo']);
        $this->assertRegExp('#<input type="submit" value="Очистить">#', $result['cartInfo']);
        
        $session->remove($key);
        $session->close();
    }
    
    /**
     * Тестирует метод PurchaseSaveService::handle
     * если запрос GET
     */
    public function testHandleGet()
    {
        $request = new class() extends Request {
            public $seocode;
            public $isAjax = false;
            public function get($name = null, $defaultValue = null)
            {
                return $this->seocode;
            }
        };
        $reflection = new \ReflectionProperty($request, 'seocode');
        $reflection->setValue($request, self::$dbClass->products['product_1']['seocode']);
        
        $service = new PurchaseSaveService();
        
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('product', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('view', $result);
    }
    
    /**
     * Тестирует метод PurchaseSaveService::handle
     * если запрос AJAX с ошибками
     */
    public function testHandleAjaxErrors()
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
        
        $service = new PurchaseSaveService();
        
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('purchaseform-quantity', $result);
    }
    
    /**
     * Тестирует метод PurchaseSaveService::handle
     * если запрос AJAX
     */
    public function testHandleAjax()
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
        
        $service = new PurchaseSaveService();
        
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('successInfo', $result);
        $this->assertArrayHasKey('cartInfo', $result);
        $this->assertInternalType('string', $result['successInfo']);
        $this->assertInternalType('string', $result['cartInfo']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
