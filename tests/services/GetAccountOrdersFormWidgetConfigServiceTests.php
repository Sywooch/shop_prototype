<?php

namespace app\tests\services;

use PHPUnit\Framework\TestCase;
use app\services\GetAccountOrdersFormWidgetConfigService;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    PurchasesFixture,
    UsersFixture};
use app\models\{CurrencyModel,
    UsersModel};
use app\forms\PurchaseForm;

/**
 * Тестирует класс GetAccountOrdersFormWidgetConfigService
 */
class GetAccountOrdersFormWidgetConfigServiceTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class,
                'users'=>UsersFixture::class,
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
     * Тестирует свойства GetAccountOrdersFormWidgetConfigService
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(GetAccountOrdersFormWidgetConfigService::class);
        
        $this->assertTrue($reflection->hasProperty('accountOrdersFormWidgetArray'));
    }
    
    /**
     * Тестирует метод  GetAccountOrdersFormWidgetConfigService::handle
     * если передана несуществующая страница
     * @expectedException yii\web\NotFoundHttpException
     */
    public function testHandleNotExistsPage()
    {
        $user = UsersModel::findOne(1);
        \Yii::$app->user->login($user);
        
        $request = new class() {
            public function get($name=null, $defaultValue=null)
            {
                return 18;
            }
        };
        
        $service = new GetAccountOrdersFormWidgetConfigService();
        $service->handle($request);
    }
    
    /**
     * Тестирует метод  GetAccountOrdersFormWidgetConfigService::handle
     * если пользователь не аутентифицирован
     * @expectedException ErrorException
     * @expectedExceptionMessage Отсутствуют необходимые данные: user
     */
    public function testHandleIsGuest()
    {
        \Yii::$app->user->logout();
        
        $request = new class() {
            public function get($name=null, $defaultValue=null)
            {
                return null;
            }
        };
        
        $service = new GetAccountOrdersFormWidgetConfigService();
        $service->handle($request);
    }
    
    /**
     * Тестирует метод  GetAccountOrdersFormWidgetConfigService::handle
     */
    public function testHandle()
    {
        $user = UsersModel::findOne(1);
        \Yii::$app->user->login($user);
        
        $request = new class() {
            public function get($name=null, $defaultValue=null)
            {
                return null;
            }
        };
        
        $service = new GetAccountOrdersFormWidgetConfigService();
        $result = $service->handle($request);
        
        $this->assertInternalType('array', $result);
        $this->assertNotEmpty($result);
        
        $this->assertArrayHasKey('purchases', $result);
        $this->assertArrayHasKey('currency', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['purchases']);
        $this->assertInstanceOf(CurrencyModel::class, $result['currency']);
        $this->assertInstanceOf(PurchaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
