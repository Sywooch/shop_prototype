<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetAdminOrderDataWidgetConfigService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    PurchasesFixture};
use app\models\{CurrencyModel,
    PurchasesModel};
use app\forms\AdminChangeOrderForm;

/**
 * Тестирует класс GetAdminOrderDataWidgetConfigService
 */
class GetAdminOrderDataWidgetConfigServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class,
                'purchases'=>PurchasesFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
    }
    
    /**
     * Тестирует свойства GetAdminOrderDataWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetAdminOrderDataWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('adminOrderDataWidgetArray'));
    }
    
    /**
     * Тестирует метод  GetAdminOrderDataWidgetConfigService::handle
     * если request пуст
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: id
     */
    public function testHandleEmptyRequest()
    {
        $request = [];
        
        $service = new GetAdminOrderDataWidgetConfigService();
        $service->handle($request);
    }
    
    /**
     * Тестирует метод  GetAdminOrderDataWidgetConfigService::handle
     */
    public function testHandle()
    {
        $request = ['id'=>1];
        
        $service = new GetAdminOrderDataWidgetConfigService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('purchase', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(PurchasesModel::class, $result['purchase']);
        $this->assertInstanceOf(CurrencyModel::class, $result['currency']);
        $this->assertInstanceOf(AdminChangeOrderForm::class, $result['form']);
        $this->assertInternalType('string', $result['template']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
