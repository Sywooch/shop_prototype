<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\AdminOrderDetailFormService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{ColorsFixture,
    CurrencyFixture,
    DeliveriesFixture,
    PaymentsFixture,
    ProductsColorsFixture,
    ProductsSizesFixture,
    PurchasesFixture,
    SizesFixture};

/**
 * Тестирует класс AdminOrderDetailFormService
 */
class AdminOrderDetailFormServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class,
                'purchases'=>PurchasesFixture::class,
                'colors'=>ColorsFixture::class,
                'products_colors'=>ProductsColorsFixture::class,
                'sizes'=>SizesFixture::class,
                'products_sizes'=>ProductsSizesFixture::class,
                'deliveries'=>DeliveriesFixture::class,
                'payments'=>PaymentsFixture::class
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
    }
    
    /**
     * Тестирует свойства AdminOrderDetailFormService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminOrderDetailFormService::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод AdminOrderDetailFormService::handle
     * если отсутствует параметр $request
     * @expectedException ErrorException
     */
    public function testHandleEmptyRequest()
    {
        $service = new AdminOrderDetailFormService();
        $service->handle();
    }
    
    /**
     * Тестирует метод AdminOrderDetailFormService::handle
     * если в запросе ошибки
     */
    public function testHandleAjaxError()
    {
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'AdminChangeOrderForm'=>[
                        'id'=>null
                    ],
                ];
            }
        };
        
        $service = new AdminOrderDetailFormService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
    }
    
    /**
     * Тестирует метод AdminOrderDetailFormService::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public $isAjax = true;
            public function post($name = null, $defaultValue = null)
            {
                return [
                    'AdminChangeOrderForm'=>[
                        'id'=>1
                    ],
                ];
            }
        };
        
        $service = new AdminOrderDetailFormService();
        $result = $service->handle($request);

        $this->assertInternalType('string', $result);
        $this->assertNotEmpty($result);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
