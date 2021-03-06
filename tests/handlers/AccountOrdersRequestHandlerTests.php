<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AccountOrdersRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    PurchasesFixture,
    UsersFixture};
use app\controllers\AccountController;
use app\models\UsersModel;

/**
 * Тестирует класс AccountOrdersRequestHandler
 */
class AccountOrdersRequestHandlerTests extends TestCase
{
    private static $dbClass;
    private $handler;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'users'=>UsersFixture::class,
                'orders'=>PurchasesFixture::class,
                'currency'=>CurrencyFixture::class
            ],
        ]);
        self::$dbClass->loadFixtures();
        
        \Yii::$app->controller = new AccountController('account', \Yii::$app);
        
        $user = UsersModel::findOne(1);
        \Yii::$app->user->login($user);
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
        
        $this->handler = new AccountOrdersRequestHandler();
    }
    
    /**
     * Тестирует свойства AccountOrdersRequestHandler
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AccountOrdersRequestHandler::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод AccountOrdersRequestHandler::handle
     */
    public function testHandle()
    {
        $request = new class() {
            public function get($name=null, $defaultValue=null)
            {
                return null;
            }
        };
        
        $result = $this->handler->handle($request);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayhasKey('оrdersFiltersWidgetConfig', $result);
        $this->assertArrayhasKey('accountOrdersWidgetConfig', $result);
        $this->assertArrayhasKey('paginationWidgetConfig', $result);
        
        $this->assertInternalType('array', $result['оrdersFiltersWidgetConfig']);
        $this->assertInternalType('array', $result['accountOrdersWidgetConfig']);
        $this->assertInternalType('array', $result['paginationWidgetConfig']);
    }
    
    /**
     * Тестирует метод AccountOrdersRequestHandler::handle
     * не существующая страница
     * @expectedException yii\web\NotFoundHttpException
     */
    public function testHandleNotPage()
    {
        $request = new class() {
            public function get($name=null, $defaultValue=null)
            {
                return 204;
            }
        };
        
        $this->handler->handle($request);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
