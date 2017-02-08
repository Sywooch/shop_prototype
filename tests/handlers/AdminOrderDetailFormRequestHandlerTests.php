<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AdminOrderDetailFormRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\{ColorsFixture,
    CurrencyFixture,
    ProductsColorsFixture,
    ProductsSizesFixture,
    PurchasesFixture,
    SizesFixture};
use app\models\{CurrencyModel,
    PurchasesModel};
use app\forms\AdminChangeOrderForm;

/**
 * Тестирует класс AdminOrderDetailFormRequestHandler
 */
class AdminOrderDetailFormRequestHandlerTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class,
                'purchases'=>PurchasesFixture::class,
                'colors'=>ColorsFixture::class,
                'sizes'=>SizesFixture::class,
                'products_colors'=>ProductsColorsFixture::class,
                'products_sizes'=>ProductsSizesFixture::class
            ],
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
    }
    
    /**
     * Тестирует свойства AdminOrderDetailFormRequestHandler
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminOrderDetailFormRequestHandler::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод AdminOrderDetailFormRequestHandler::adminOrderDetailFormWidgetConfig
     */
    public function testAdminOrderDetailFormWidgetConfig()
    {
        $handler = new AdminOrderDetailFormRequestHandler();
        
        $reflection = new \ReflectionMethod($handler, 'adminOrderDetailFormWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($handler, 1);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('purchase', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('statuses', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('colors', $result);
        $this->assertArrayHasKey('sizes', $result);
        $this->assertArrayHasKey('deliveries', $result);
        $this->assertArrayHasKey('payments', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(PurchasesModel::class, $result['purchase']);
        $this->assertInstanceOf(CurrencyModel::class, $result['currency']);
        $this->assertInternalType('array', $result['statuses']);
        $this->assertInstanceOf(AdminChangeOrderForm::class, $result['form']);
        $this->assertInternalType('array', $result['colors']);
        $this->assertInternalType('array', $result['sizes']);
        $this->assertInternalType('array', $result['deliveries']);
        $this->assertInternalType('array', $result['payments']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AdminOrderDetailFormRequestHandler::handle
     * если не передан request
     * @expectedException ErrorException
     */
    public function testHandleEmptyRequest()
    {
        $handler = new AdminOrderDetailFormRequestHandler();
        $handler->handle();
    }
    
    /**
     * Тестирует метод AdminOrderDetailFormRequestHandler::handle
     * если пуста форма
     * @expectedException ErrorException
     */
    public function testHandleEmptyForm()
    {
        $request = new class() {
            public $isAjax = true;
            public function post($name=null, $defaultValue=null)
            {
                return [
                    'AdminChangeOrderForm'=>[
                        'id'=>null
                    ],
                ];
            }
        };
        
        $handler = new AdminOrderDetailFormRequestHandler();
        $reqult = $handler->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
    }
    
    /**
     * Тестирует метод AdminOrderDetailFormRequestHandler::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public $isAjax = true;
            public function post($name=null, $defaultValue=null)
            {
                return [
                    'AdminChangeOrderForm'=>[
                        'id'=>1
                    ],
                ];
            }
        };
        
        $handler = new AdminOrderDetailFormRequestHandler();
        $result = $handler->handle($request);
        
        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
