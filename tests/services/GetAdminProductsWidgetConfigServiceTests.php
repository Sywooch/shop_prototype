<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetAdminProductsWidgetConfigService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    ProductsFixture};
use app\models\CurrencyModel;
use app\forms\AdminChangeProductForm;
use app\controllers\AdminController;

/**
 * Тестирует класс GetAdminProductsWidgetConfigService
 */
class GetAdminProductsWidgetConfigServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class,
                'products'=>ProductsFixture::class,
            ]
        ]);
        self::$dbClass->loadFixtures();
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
    }
    
    /**
     * Тестирует свойства GetAdminProductsWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetAdminProductsWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('adminProductsWidgetArray'));
    }
    
    /**
     * Тестирует метод  GetAdminProductsWidgetConfigService::handle
     * если передана несуществующая страница
     * @expectedException yii\web\NotFoundHttpException
     */
    public function testHandleNotExistsPage()
    {
        \Yii::$app->controller = new AdminController('admin', \Yii::$app);
        
        $request = new class() {
            public function get($name=null, $defaultValue=null)
            {
                return 18;
            }
        };
        
        $service = new GetAdminProductsWidgetConfigService();
        $service->handle($request);
    }
    
    /**
     * Тестирует метод  GetAdminProductsWidgetConfigService::handle
     */
    public function testHandle()
    {
        \Yii::$app->controller = new AdminController('admin', \Yii::$app);
        
        $request = new class() {
            public function get($name=null, $defaultValue=null)
            {
                return null;
            }
        };
        
        $service = new GetAdminProductsWidgetConfigService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('products', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('array', $result['products']);
        $this->assertInstanceOf(CurrencyModel::class, $result['currency']);
        $this->assertInstanceOf(AdminChangeProductForm::class, $result['form']);
        $this->assertInternalType('string', $result['template']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
