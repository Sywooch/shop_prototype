<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\CartIndexRequestHandler;
use app\helpers\HashHelper;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    ProductsFixture};
use app\controllers\CartController;

/**
 * Тестирует класс CartIndexRequestHandler
 */
class CartIndexRequestHandlerTests extends TestCase
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
        
        $this->handler = new CartIndexRequestHandler();
    }
    
    /**
     * Тестирует метод CartIndexRequestHandler::cartCheckoutLinkWidgetConfig
     */
    public function testCartCheckoutLinkWidgetConfig()
    {
        $reflection = new \ReflectionMethod($this->handler, 'cartCheckoutLinkWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('template', $result);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод CartIndexRequestHandler::handle
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
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('cartWidgetConfig', $result);
        $this->assertArrayHasKey('userInfoWidgetConfig', $result);
        //$this->assertArrayHasKey('shortCartRedirectWidgetConfig', $result);
        $this->assertArrayHasKey('currencyWidgetConfig', $result);
        $this->assertArrayHasKey('searchWidgetConfig', $result);
        $this->assertArrayHasKey('categoriesMenuWidgetConfig', $result);
        $this->assertArrayHasKey('cartCheckoutLinkWidgetConfig', $result);
        $this->assertInternalType('array', $result['cartWidgetConfig']);
        $this->assertInternalType('array', $result['userInfoWidgetConfig']);
        //$this->assertInternalType('array', $result['shortCartRedirectWidgetConfig']);
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
