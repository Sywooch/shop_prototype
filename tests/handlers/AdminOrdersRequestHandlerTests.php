<?php

namespace app\tests\handlers;

use PHPUnit\Framework\TestCase;
use app\handlers\AdminOrdersRequestHandler;
use app\tests\DbManager;
use app\tests\sources\fixtures\{CurrencyFixture,
    PurchasesFixture};
use app\forms\{AdminChangeOrderForm,
    OrdersFiltersForm};
use app\filters\OrdersFilters;
use app\controllers\AdminController;
use app\collections\{LightPagination,
    PaginationInterface,
    PurchasesCollection};
use app\models\CurrencyModel;

/**
 * Тестирует класс AdminOrdersRequestHandler
 */
class AdminOrdersRequestHandlerTests extends TestCase
{
    private static $dbClass;
    
    public static function setUpBeforeClass()
    {
        self::$dbClass = new DbManager([
            'fixtures'=>[
                'currency'=>CurrencyFixture::class,
                'purchases'=>PurchasesFixture::class,
            ],
        ]);
        self::$dbClass->loadFixtures();
        
        \Yii::$app->controller = new AdminController('admin', \Yii::$app);
    }
    
    public function setUp()
    {
        \Yii::$app->registry->clean();
    }
    
    /**
     * Тестирует свойства AdminOrdersRequestHandler
     */
    public function testProperties()
    {
        $reflection = new \ReflectionClass(AdminOrdersRequestHandler::class);
        
        $this->assertTrue($reflection->hasProperty('dataArray'));
    }
    
    /**
     * Тестирует метод AdminOrdersRequestHandler::оrdersFiltersWidgetConfig
     */
    public function testOrdersFiltersWidgetConfig()
    {
        $filters = new class() extends OrdersFilters {};
        
        $handler = new AdminOrdersRequestHandler();
        
        $reflection = new \ReflectionMethod($handler, 'оrdersFiltersWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($handler, $filters);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('sortingTypes', $result);
        $this->assertArrayHasKey('statuses', $result);
        $this->assertArrayHasKey('form', $result);
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('array', $result['sortingTypes']);
        $this->assertInternalType('array', $result['statuses']);
        $this->assertInstanceOf(OrdersFiltersForm::class, $result['form']);
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AdminOrdersRequestHandler::adminOrdersWidgetConfig
     * если заказов нет
     */
    public function testAdminOrdersWidgetConfigEmptyOrders()
    {
        $collection = new class() extends PurchasesCollection {
            public $pagination;
            public function __construct()
            {
                $this->pagination = new class() {
                    public $totalCount = 0;
                };
            }
            public function isEmpty()
            {
                return true;
            }
        };
        
        $handler = new AdminOrdersRequestHandler();
        
        $reflection = new \ReflectionMethod($handler, 'adminOrdersWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($handler, $collection);
        
        $this->assertInternalType('array', $result);
        
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
    
    /**
     * Тестирует метод AdminOrdersRequestHandler::adminOrdersWidgetConfig
     * если передана несуществующая страница
     * @expectedException yii\web\NotFoundHttpException
     */
    public function testAdminOrdersWidgetConfigNotPage()
    {
        $collection = new class() extends PurchasesCollection {
            public $pagination;
            public function __construct()
            {
                $this->pagination = new class() {
                    public $totalCount = 10;
                };
            }
            public function isEmpty()
            {
                return true;
            }
        };
        
        $handler = new AdminOrdersRequestHandler();
        
        $reflection = new \ReflectionMethod($handler, 'adminOrdersWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($handler, $collection);
    }
    
    /**
     * Тестирует метод AdminOrdersRequestHandler::adminOrdersWidgetConfig
     */
    public function testAdminOrdersWidgetConfig()
    {
        $collection = new class() extends PurchasesCollection {
            public $pagination;
            public function __construct()
            {
                $this->pagination = new class() {
                    public $totalCount = 2;
                };
            }
            public function isEmpty()
            {
                return false;
            }
        };
        
        $handler = new AdminOrdersRequestHandler();
        
        $reflection = new \ReflectionMethod($handler, 'adminOrdersWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($handler, $collection);
        
        $this->assertInternalType('array', $result);
        
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
    
    /**
     * Тестирует метод AdminOrdersRequestHandler::paginationWidgetConfig
     */
    public function testPaginationWidgetConfig()
    {
        $pagination = new class() extends LightPagination {};
        
        $handler = new AdminOrdersRequestHandler();
        
        $reflection = new \ReflectionMethod($handler, 'paginationWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($handler, $pagination);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('pagination', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInstanceOf(PaginationInterface::class, $result['pagination']);
        $this->assertInternalType('string', $result['template']);
    }
    
    /**
     * Тестирует метод AdminOrdersRequestHandler::adminCsvOrdersFormWidgetConfig
     */
    public function testAdminCsvOrdersFormWidgetConfig()
    {
        $handler = new AdminOrdersRequestHandler();
        
        $reflection = new \ReflectionMethod($handler, 'adminCsvOrdersFormWidgetConfig');
        $reflection->setAccessible(true);
        $result = $reflection->invoke($handler);
        
        $this->assertInternalType('array', $result);
        
        $this->assertArrayHasKey('header', $result);
        $this->assertArrayHasKey('template', $result);
        
        $this->assertInternalType('string', $result['header']);
        $this->assertInternalType('string', $result['template']);
    }
    
    public static function tearDownAfterClass()
    {
        self::$dbClass->unloadFixtures();
    }
}
