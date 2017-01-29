<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetAdminOrdersFormWidgetConfigService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    PurchasesFixture};
use app\models\CurrencyModel;
use app\forms\AdminChangeOrderForm;

/**
 * Тестирует класс GetAdminOrdersFormWidgetConfigService
 */
class GetAdminOrdersFormWidgetConfigServiceTests extends TestCase
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
     * Тестирует свойства GetAdminOrdersFormWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetAdminOrdersFormWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('adminOrdersFormWidgetArray'));
    }
    
    /**
     * Тестирует метод  GetAdminOrdersFormWidgetConfigService::handle
     * если передана несуществующая страница
     * @expectedException yii\web\NotFoundHttpException
     */
    public function testHandleNotExistsPage()
    {
        $request = new class() {
            public function get($name=null, $defaultValue=null)
            {
                return 18;
            }
        };
        
        $service = new GetAdminOrdersFormWidgetConfigService();
        $service->handle($request);
    }
    
    /**
     * Тестирует метод  GetAdminOrdersFormWidgetConfigService::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public function get($name=null, $defaultValue=null)
            {
                return null;
            }
        };
        
        $service = new GetAdminOrdersFormWidgetConfigService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('purchases', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('array', $result['purchases']);
        $this->assertInstanceOf(CurrencyModel::class, $result['currency']);
        $this->assertInstanceOf(AdminChangeOrderForm::class, $result['form']);
        $this->assertInternalType('string', $result['template']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
