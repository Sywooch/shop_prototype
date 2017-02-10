<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AccountOrdersRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    PurchasesFixture,
    UsersFixture};
use app\filters\OrdersFilters;
use app\forms\{OrdersFiltersForm,
    PurchaseForm};
use app\controllers\AccountController;
use app\models\{CurrencyInterface,
    CurrencyModel,
    UsersModel};
use app\collections\{LightPagination,
    PaginationInterface};

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
     * Тестирует метод AccountOrdersRequestHandler::оrdersFiltersWidgetConfig
     */
    public function testOrdersFiltersWidgetConfig()
    {
        $filtersModel = new class() extends OrdersFilters {};
        
        $reflection = new \ReflectionMethod($this->handler, 'оrdersFiltersWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $filtersModel);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayhasKey('sortingTypes', $result);
        $this->assertArrayhasKey('statuses', $result);
        $this->assertArrayhasKey('form', $result);
        $this->assertArrayhasKey('header', $result);
        $this->assertArrayhasKey('template', $result);
        
        $this->assertInternalType('array', $result['sortingTypes']);
        $this->assertInternalType('array', $result['statuses']);
        $this->assertInstanceOf(OrdersFiltersForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AccountOrdersRequestHandler::accountOrdersWidgetConfig
     */
    public function testAccountOrdersWidgetConfig()
    {
        $ordersArray = [new class() {}];
        $currentCurrencyModel = new class() extends CurrencyModel {};
        
        $reflection = new \ReflectionMethod($this->handler, 'accountOrdersWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $ordersArray, $currentCurrencyModel);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayhasKey('header', $result);
        $this->assertArrayhasKey('purchases', $result);
        $this->assertArrayhasKey('currency', $result);
        $this->assertArrayhasKey('form', $result);
        $this->assertArrayhasKey('template', $result);
        
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('array', $result['purchases']);
        $this->assertInstanceOf(CurrencyInterface::class, $result['currency']);
        $this->assertInstanceOf(PurchaseForm::class, $result['form']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AccountOrdersRequestHandler::paginationWidgetConfig
     */
    public function testPaginationWidgetConfig()
    {
        $pagination = new class extends LightPagination {};
        
        $reflection = new \ReflectionMethod($this->handler, 'paginationWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($this->handler, $pagination);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayhasKey('pagination', $result);
        $this->assertArrayhasKey('template', $result);
        
        $this->assertInstanceOf(PaginationInterface::class, $result['pagination']);
        $this->assertInternalType('string', $result['template']);
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
