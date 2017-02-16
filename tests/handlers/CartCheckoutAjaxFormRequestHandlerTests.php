<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\CartCheckoutAjaxFormRequestHandler;
use app\helpers\HashHelper;
use app\tests\DbManager;
use app\tests\sources\fixtures\{DeliveriesFixture,
    CurrencyFixture,
    PaymentsFixture};
use app\models\{CurrencyInterface,
    CurrencyModel};
use app\forms\AbstractBaseForm;

/**
 * Тестирует класс CartCheckoutAjaxFormRequestHandler
 */
class CartCheckoutAjaxFormRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class,
                'deliveries'=>DeliveriesFixture::class,
                'payments'=>PaymentsFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        \Yii::$app->user->logout();
        
        $this->handler = new CartCheckoutAjaxFormRequestHandler();
    }
    
    /**
     * Тестирует метод CartCheckoutAjaxFormRequestHandler::cartCheckoutWidgetConfig
     */
    public function testCartCheckoutWidgetConfig()
    {
        $deliveriesArray = [new class() {}];
        $paymentsArray = [new class() {}];
        $currentCurrencyModel = new class() extends CurrencyModel {};
        $customerInfoForm = new class() extends AbstractBaseForm {};
        
        $reflection = new \ReflectionMethod($this->handler, 'cartCheckoutWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $deliveriesArray, $paymentsArray, $currentCurrencyModel, $customerInfoForm);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('deliveries', $result);
        $this->assertArrayHasKey('payments', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['deliveries']);
        $this->assertInternalType('array', $result['payments']);
        $this->assertInstanceOf(CurrencyInterface::class, $result['currency']);
        $this->assertInstanceOf(AbstractBaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод CartCheckoutAjaxFormRequestHandler::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public $isAjax = true;
            public function get($name=null, $defaultValue=null)
            {
                return null;
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
