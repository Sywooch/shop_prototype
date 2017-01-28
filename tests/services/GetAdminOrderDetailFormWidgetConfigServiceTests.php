<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetAdminOrderDetailFormWidgetConfigService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{ColorsFixture,
    CurrencyFixture,
    DeliveriesFixture,
    PaymentsFixture,
    ProductsColorsFixture,
    ProductsSizesFixture,
    PurchasesFixture,
    SizesFixture};
use app\models\{CurrencyModel,
    PurchasesModel};
use app\forms\AdminChangeOrderForm;

/**
 * Тестирует класс GetAdminOrderDetailFormWidgetConfigService
 */
class GetAdminOrderDetailFormWidgetConfigServiceTests extends TestCase
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
     * Тестирует свойства GetAdminOrderDetailFormWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetAdminOrderDetailFormWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('adminOrderDetailFormWidgetArray'));
    }
    
    /**
     * Тестирует метод GetAdminOrderDetailFormWidgetConfigService::handle
     * если передан пустой request
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: id
     */
    public function testHandleNotExistsPage()
    {
        $request = [];
        
        $service = new GetAdminOrderDetailFormWidgetConfigService();
        $service->handle($request);
    }
    
    /**
     * Тестирует метод  GetAdminOrderDetailFormWidgetConfigService::handle
     */
    public function testHandle()
    {
        $request = ['id'=>1];
        
        $service = new GetAdminOrderDetailFormWidgetConfigService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
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
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
