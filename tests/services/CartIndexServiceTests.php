<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\CartIndexService;
use app\helpers\HashHelper;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    ProductsFixture};
use app\controllers\CartController;

/**
 * Тестирует класс CartIndexService
 */
class CartIndexServiceTests extends TestCase
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
     * Тестирует метод CartIndexService::handle
     */
    public function testHandle()
    {
        \Yii::$app->controller = new CartController('cart', \Yii::$app);
        
        $key = HashHelper::createCartKey();
        
        $session = \Yii::$app->session;
        $session->open();
        $session->set($key, [['quantity'=>2, 'id_color'=>2, 'id_size'=>2, 'id_product'=>1, 'price'=>268.78]]);
        
        $request = new class() {
            public function get($name = null, $defaultValue = null)
            {
                return null;
            }
        };
        
        $service = new CartIndexService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('cartWidgetConfig', $result);
        $this->assertArrayHasKey('userInfoWidgetConfig', $result);
        $this->assertArrayHasKey('shortCartRedirectWidgetConfig', $result);
        $this->assertArrayHasKey('currencyWidgetConfig', $result);
        $this->assertArrayHasKey('searchWidgetConfig', $result);
        $this->assertArrayHasKey('categoriesMenuWidgetConfig', $result);
        $this->assertArrayHasKey('cartCheckoutLinkWidgetConfig', $result);
        $this->assertInternalType('array', $result['cartWidgetConfig']);
        $this->assertInternalType('array', $result['userInfoWidgetConfig']);
        $this->assertInternalType('array', $result['shortCartRedirectWidgetConfig']);
        $this->assertInternalType('array', $result['currencyWidgetConfig']);
        $this->assertInternalType('array', $result['searchWidgetConfig']);
        $this->assertInternalType('array', $result['categoriesMenuWidgetConfig']);
        $this->assertInternalType('array', $result['cartCheckoutLinkWidgetConfig']);
        
        $session->remove($key);
        $session->close();
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
