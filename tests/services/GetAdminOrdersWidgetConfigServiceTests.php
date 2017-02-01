<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetAdminOrdersWidgetConfigService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    PurchasesFixture};
use app\models\CurrencyModel;
use app\forms\AdminChangeOrderForm;

/**
 * Тестирует класс GetAdminOrdersWidgetConfigService
 */
class GetAdminOrdersWidgetConfigServiceTests extends TestCase
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
     * Тестирует свойства GetAdminOrdersWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetAdminOrdersWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('adminOrdersWidgetArray'));
    }
    
    /**
     * Тестирует метод  GetAdminOrdersWidgetConfigService::handle
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
        
        $service = new GetAdminOrdersWidgetConfigService();
        $service->handle($request);
    }
    
    /**
     * Тестирует метод  GetAdminOrdersWidgetConfigService::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public function get($name=null, $defaultValue=null)
            {
                return null;
            }
        };
        
        $service = new GetAdminOrdersWidgetConfigService();
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
